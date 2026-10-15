<?php

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Splitice\Rage4\CreatedDomain;
use Splitice\Rage4\CreatedRecord;
use Splitice\Rage4\Domain;
use Splitice\Rage4\Rage4Api;
use Splitice\Rage4\Record;
use Splitice\Rage4\Sync\SimpleSync;

final class SimpleSyncBehaviorTest extends TestCase
{
    /**
     * @param int $id
     * @param string $name
     * @param string $content
     * @param string $type
     * @return array<string, mixed>
     */
    private function recordData($id, $name, $content, $type = 'A')
    {
        return array(
            'id' => $id,
            'domain_id' => 42,
            'name' => $name,
            'content' => $content,
            'type' => $type,
            'priority' => 1,
            'failover_enabled' => false,
            'failover_content' => '',
            'ttl' => 300,
            'geo_region_id' => 0,
            'geo_lat' => null,
            'geo_long' => null,
        );
    }

    /**
     * @param array<int, Record> $records
     * @return CreatedDomain
     */
    private function domain(array $records)
    {
        return new CreatedDomain(
            'example.com',
            Domain::TYPE_DOMAIN,
            'hostmaster@example.com',
            42,
            $records
        );
    }

    /**
     * @return void
     */
    public function testSyncAddsMissingRecordAndLogsTheChange()
    {
        $api = $this->createMock(Rage4Api::class);
        $api->expects($this->once())->method('getRecords')->with(42)->willReturn(array());
        $api->expects($this->once())->method('createRecord')->willReturn(array('id' => 9));
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info')->with('Adding www.example.com');
        $domain = $this->domain(array(new Record('www.example.com', '192.0.2.10', 'A')));
        $sync = new SimpleSync($api, $logger);

        $sync->sync_domain($domain, false);

        $this->assertInstanceOf(CreatedRecord::class, $domain->records[0]);
        $this->assertSame(9, $domain->records[0]->id);
    }

    /**
     * @return void
     */
    public function testSyncReusesEquivalentRemoteRecord()
    {
        $remote = $this->recordData(9, 'www.example.com', '192.0.2.10');
        $api = $this->createMock(Rage4Api::class);
        $api->method('getRecords')->willReturn(array($remote));
        $api->expects($this->never())->method('createRecord');
        $api->expects($this->never())->method('updateRecord');
        $domain = $this->domain(array(new Record('www.example.com', '192.0.2.10', 'A', 1, false, '', 300)));
        $sync = new SimpleSync($api);

        $sync->sync_domain($domain, false);

        $this->assertInstanceOf(CreatedRecord::class, $domain->records[0]);
        $this->assertSame(9, $domain->records[0]->id);
    }

    /**
     * @return void
     */
    public function testSyncUpdatesChangedCreatedRecordAndLogsTheChange()
    {
        $remote = $this->recordData(9, 'www.example.com', '192.0.2.10');
        $desired = new CreatedRecord(9, 'www.example.com', '198.51.100.20', 'A', 1, false, '', 300, 42);
        $api = $this->createMock(Rage4Api::class);
        $api->method('getRecords')->willReturn(array($remote));
        $api->expects($this->once())->method('updateRecord')->with(
            9,
            'www.example.com',
            '198.51.100.20',
            1,
            false,
            '',
            300,
            0,
            null,
            null
        )->willReturn(array('status' => true));
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info')->with('Updating www.example.com');
        $sync = new SimpleSync($api, $logger);

        $sync->sync_domain($this->domain(array($desired)), false);
    }

    /**
     * @return void
     */
    public function testSyncDeletesOnlyUnwantedNonInfrastructureRecords()
    {
        $address = $this->recordData(9, 'old.example.com', '192.0.2.10');
        $soa = $this->recordData(10, 'example.com', 'ns1.example.com', 'SOA');
        $ns = $this->recordData(11, 'example.com', 'ns1.example.com', 'NS');
        $api = $this->createMock(Rage4Api::class);
        $api->expects($this->exactly(2))->method('getRecords')
            ->willReturnOnConsecutiveCalls(array($address, $soa, $ns), array($address, $soa, $ns));
        $api->expects($this->once())->method('deleteRecord')->with(9);
        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('info')->with('Deleting old.example.com:192.0.2.10');
        $sync = new SimpleSync($api, $logger);

        $sync->sync_domain($this->domain(array()), true);
    }

    /**
     * @return void
     */
    public function testDeletePhaseKeepsDesiredCreatedRecord()
    {
        $remote = $this->recordData(9, 'www.example.com', '192.0.2.10');
        $desired = new CreatedRecord(9, 'www.example.com', '192.0.2.10', 'A', 1, false, '', 300, 42);
        $api = $this->createMock(Rage4Api::class);
        $api->expects($this->exactly(2))->method('getRecords')->willReturn(array($remote));
        $api->expects($this->never())->method('deleteRecord');
        $sync = new SimpleSync($api);

        $sync->sync_domain($this->domain(array($desired)), true);
    }

    /**
     * @return void
     */
    public function testDeletePhaseRejectsUnresolvedPlainRecords()
    {
        $remote = $this->recordData(9, 'old.example.com', '192.0.2.10');
        $api = $this->createMock(Rage4Api::class);
        $api->expects($this->exactly(2))->method('getRecords')->willReturn(array($remote));
        $domain = $this->domain(array(new Record('wanted.example.com', '198.51.100.8', 'A')));
        $sync = new DeleteOnlySync($api);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('all records should be created');

        $sync->sync_domain($domain, true);
    }
}

final class DeleteOnlySync extends SimpleSync
{
    /**
     * Preserve the invalid pre-delete state used to verify the safety guard.
     *
     * @param CreatedDomain $domain
     * @return void
     */
    protected function handle_records(CreatedDomain $domain)
    {
    }
}
