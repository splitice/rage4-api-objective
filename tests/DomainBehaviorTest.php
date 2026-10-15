<?php

use PHPUnit\Framework\TestCase;
use Splitice\Rage4\CreatedDomain;
use Splitice\Rage4\CreatedRecord;
use Splitice\Rage4\Domain;
use Splitice\Rage4\Rage4Api;
use Splitice\Rage4\Rage4Exception;
use Splitice\Rage4\Record;
use Splitice\Rage4\Sync\IDomainSync;

final class DomainBehaviorTest extends TestCase
{
    /**
     * @return array<string, mixed>
     */
    private function domainData()
    {
        return array(
            'id' => 42,
            'name' => 'example.com',
            'type' => Domain::TYPE_DOMAIN,
            'owner_email' => 'hostmaster@example.com',
            'subnet_mask' => 0,
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function recordData()
    {
        return array(
            'id' => 9,
            'domain_id' => 42,
            'name' => 'www.example.com',
            'content' => '192.0.2.10',
            'type' => 'A',
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
     * @return void
     */
    public function testDomainCreationReturnsCreatedDomainWithOriginalConfiguration()
    {
        $api = $this->createMock(Rage4Api::class);
        $api->expects($this->once())->method('createDomain')
            ->with('example.com', 'hostmaster@example.com', 'dns.example.net')
            ->willReturn(array('id' => 42));
        $domain = new Domain('example.com', Domain::TYPE_DOMAIN, 'hostmaster@example.com', array(), 'dns.example.net');

        $created = $domain->create($api);

        $this->assertInstanceOf(CreatedDomain::class, $created);
        $this->assertSame(42, $created->id);
        $this->assertSame('dns.example.net', $created->nsdomain);
    }

    /**
     * @return void
     */
    public function testDomainCreationReturnsApiErrorUnchanged()
    {
        $api = $this->createMock(Rage4Api::class);
        $api->method('createDomain')->willReturn('duplicate domain');
        $domain = new Domain('example.com', Domain::TYPE_DOMAIN, 'hostmaster@example.com');

        $this->assertSame('duplicate domain', $domain->create($api));
    }

    /**
     * @return void
     */
    public function testGetReturnsExistingDomainWithoutCreatingIt()
    {
        $api = $this->createMock(Rage4Api::class);
        $api->expects($this->once())->method('getDomainByName')->willReturn($this->domainData());
        $api->expects($this->never())->method('createDomain');
        $domain = new Domain('example.com', Domain::TYPE_DOMAIN, 'hostmaster@example.com');

        $this->assertSame(42, $domain->get($api)->id);
    }

    /**
     * @return void
     */
    public function testGetCreatesDomainWhenItDoesNotExist()
    {
        $api = $this->createMock(Rage4Api::class);
        $api->method('getDomainByName')->willReturn('not found');
        $api->expects($this->once())->method('createDomain')->willReturn(array('id' => 43));
        $domain = new Domain('new.example.com', Domain::TYPE_DOMAIN, 'hostmaster@example.com');

        $this->assertSame(43, $domain->get($api)->id);
    }

    /**
     * @return void
     */
    public function testGetAllMapsApiDomainsToObjects()
    {
        $api = $this->createMock(Rage4Api::class);
        $api->method('getDomains')->willReturn(array($this->domainData()));

        $domains = Domain::getAll($api);

        $this->assertCount(1, $domains);
        $this->assertSame('example.com', $domains[0]->name);
        $this->assertSame(42, $domains[0]->id);
    }

    /**
     * @return void
     */
    public function testGetAllRejectsApiErrors()
    {
        $api = $this->createMock(Rage4Api::class);
        $api->method('getDomains')->willReturn('permission denied');

        $this->expectException(Rage4Exception::class);
        $this->expectExceptionMessage('permission denied');

        Domain::getAll($api);
    }

    /**
     * @dataProvider lookupProvider
     *
     * @param string $method
     * @param string $apiMethod
     * @param mixed $query
     * @return void
     */
    public function testLookupsMapApiDomain($method, $apiMethod, $query)
    {
        $api = $this->createMock(Rage4Api::class);
        $api->expects($this->once())->method($apiMethod)->with($query)->willReturn($this->domainData());

        $domain = call_user_func(array(Domain::class, $method), $api, $query);

        $this->assertInstanceOf(CreatedDomain::class, $domain);
        $this->assertSame(42, $domain->id);
    }

    /**
     * @dataProvider lookupProvider
     *
     * @param string $method
     * @param string $apiMethod
     * @param mixed $query
     * @return void
     */
    public function testLookupsReturnNullForEverySupportedMissingResponse($method, $apiMethod, $query)
    {
        foreach (array('Unable to fetch the item or API access not allowed', 'not found', null) as $response) {
            $api = $this->createMock(Rage4Api::class);
            $api->method($apiMethod)->willReturn($response);

            $this->assertNull(call_user_func(array(Domain::class, $method), $api, $query));
        }
    }

    /**
     * @dataProvider lookupProvider
     *
     * @param string $method
     * @param string $apiMethod
     * @param mixed $query
     * @return void
     */
    public function testLookupsRejectUnexpectedApiErrors($method, $apiMethod, $query)
    {
        $api = $this->createMock(Rage4Api::class);
        $api->method($apiMethod)->willReturn('rate limited');

        $this->expectException(Rage4Exception::class);
        $this->expectExceptionMessage('rate limited');

        call_user_func(array(Domain::class, $method), $api, $query);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function lookupProvider()
    {
        return array(
            'by name' => array('fromName', 'getDomainByName', 'example.com'),
            'by id' => array('fromId', 'getDomain', 42),
        );
    }

    /**
     * @return void
     */
    public function testCreatedDomainExposesLifecycleBehaviors()
    {
        $api = $this->createMock(Rage4Api::class);
        $api->expects($this->once())->method('deleteDomain')->with(42);
        $api->expects($this->once())->method('createRecord')->willReturn(array('id' => 9));
        $sync = $this->createMock(IDomainSync::class);
        $domain = new CreatedDomain('example.com', Domain::TYPE_DOMAIN, 'hostmaster@example.com', 42);
        $record = new Record('www.example.com', '192.0.2.10', 'A');
        $sync->expects($this->once())->method('sync_domain')->with($domain, false);

        $domain->delete($api);
        $createdRecord = $domain->add_record($record, $api);
        $domain->sync($sync, false);

        $this->assertSame($domain, $domain->get($api));
        $this->assertSame(9, $createdRecord->id);
    }

    /**
     * @return void
     */
    public function testRecordListsCanBeCachedRefreshedAndSearched()
    {
        $first = $this->recordData();
        $second = $first;
        $second['id'] = 10;
        $second['name'] = 'mail.example.com';
        $second['type'] = 'MX';
        $api = $this->createMock(Rage4Api::class);
        $api->expects($this->exactly(2))->method('getRecords')
            ->willReturnOnConsecutiveCalls(array($first, $second), array($first));
        $domain = new CreatedDomain('example.com', Domain::TYPE_DOMAIN, 'hostmaster@example.com', 42);

        $cached = $domain->get_records($api, true);
        $this->assertSame($cached, $domain->get_records($api, true));
        $this->assertSame(9, $domain->get_record($api, 'www.example.com', 'A', false, true)->id);
        $matches = $domain->get_record($api, 'mail.example.com', '`^M[X]$`', true, true);
        $this->assertCount(1, $matches);
        $this->assertSame(array(), $domain->get_record($api, 'missing.example.com', null, true, true));
        $this->assertNull($domain->get_record($api, 'missing.example.com', '', false, true));
        $this->assertNull($domain->get_record($api, 'www.example.com', '', false, true));

        $domain->refresh_records($api);

        $this->assertCount(1, $domain->records);
        $this->assertSame(9, $domain->records[0]->id);
    }

    /**
     * @return void
     */
    public function testRecordListRejectsApiErrors()
    {
        $api = $this->createMock(Rage4Api::class);
        $api->method('getRecords')->willReturn('domain unavailable');
        $domain = new CreatedDomain('example.com', Domain::TYPE_DOMAIN, 'hostmaster@example.com', 42);

        $this->expectException(Rage4Exception::class);
        $this->expectExceptionMessage('domain unavailable');

        $domain->get_records($api);
    }
}
