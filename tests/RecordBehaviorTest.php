<?php

use PHPUnit\Framework\TestCase;
use Splitice\Rage4\CreatedDomain;
use Splitice\Rage4\CreatedRecord;
use Splitice\Rage4\Domain;
use Splitice\Rage4\Rage4Api;
use Splitice\Rage4\Rage4Exception;
use Splitice\Rage4\Record;

final class RecordBehaviorTest extends TestCase
{
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
            'priority' => 10,
            'failover_enabled' => true,
            'failover_content' => '192.0.2.11',
            'ttl' => 300,
            'geo_region_id' => 7,
            'geo_lat' => 51.5,
            'geo_long' => -0.12,
        );
    }

    /**
     * @return void
     */
    public function testConstructorNormalizesContentPriorityAndGeoCoordinates()
    {
        $record = new Record('www.example.com', 1234, 'A', null, null, null, 300, 0, 1.2, 3.4, true);
        $geoRecord = new Record('geo.example.com', '192.0.2.1', 'A', 5, false, '', 60, 7, 1.2, 3.4);

        $this->assertSame('1234', $record->content);
        $this->assertSame(Record::DEFAULT_PRIORITY, $record->priority);
        $this->assertNull($record->geolat);
        $this->assertNull($record->geolong);
        $this->assertTrue($record->geolock);
        $this->assertSame(1.2, $geoRecord->geolat);
        $this->assertSame(3.4, $geoRecord->geolong);
    }

    /**
     * @return void
     */
    public function testRecordGetRequiresDomain()
    {
        $record = new Record('www.example.com', '192.0.2.10', 'A');
        $api = $this->createMock(Rage4Api::class);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('A domain is required');

        $record->get($api);
    }

    /**
     * @return void
     */
    public function testRecordGetFiltersByNameAndType()
    {
        $api = $this->createMock(Rage4Api::class);
        $api->method('getRecords')->willReturn(array($this->recordData()));
        $domain = new CreatedDomain('example.com', Domain::TYPE_DOMAIN, 'hostmaster@example.com', 42);
        $record = new Record('www.example.com', 'different content', 'A');

        $matches = $record->get($api, $domain);

        $this->assertCount(1, $matches);
        $this->assertSame(9, $matches[0]->id);
        $this->assertSame(9, $record->get($api, $domain, true)->id);
    }

    /**
     * @return void
     */
    public function testSingleRecordGetRejectsNonUniqueResults()
    {
        $api = $this->createMock(Rage4Api::class);
        $api->method('getRecords')->willReturn(array());
        $domain = new CreatedDomain('example.com', Domain::TYPE_DOMAIN, 'hostmaster@example.com', 42);
        $record = new Record('www.example.com', '192.0.2.10', 'A');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('More than one record found!');

        $record->get($api, $domain, true);
    }

    /**
     * @return void
     */
    public function testUpgradeAndApiDataPreserveRecordProperties()
    {
        $plain = new Record('geo.example.com', '192.0.2.10', 'A', 10, true, '192.0.2.11', 300, 7, 51.5, -0.12);
        $upgraded = $plain->upgradeWithId(9);
        $fromApi = Record::fromRage4($this->recordData());

        $this->assertInstanceOf(CreatedRecord::class, $upgraded);
        $this->assertSame(9, $upgraded->id);
        $this->assertTrue($plain->equals($upgraded));
        $this->assertSame(42, $fromApi->domain);
        $this->assertSame(7, $fromApi->geo);
        $this->assertSame(51.5, $fromApi->geolat);
    }

    /**
     * @return void
     */
    public function testCreateReturnsCreatedRecord()
    {
        $api = $this->createMock(Rage4Api::class);
        $api->expects($this->once())->method('createRecord')->willReturn(array('id' => 9));
        $domain = new CreatedDomain('example.com', Domain::TYPE_DOMAIN, 'hostmaster@example.com', 42);
        $record = new Record('www.example.com', '192.0.2.10', 'A');

        $created = $record->create($domain, $api);

        $this->assertSame(9, $created->id);
        $this->assertTrue($record->equals($created));
    }

    /**
     * @dataProvider createErrorProvider
     *
     * @param mixed $response
     * @param string $message
     * @return void
     */
    public function testCreateRejectsApiErrors($response, $message)
    {
        $api = $this->createMock(Rage4Api::class);
        $api->method('createRecord')->willReturn($response);
        $domain = new CreatedDomain('example.com', Domain::TYPE_DOMAIN, 'hostmaster@example.com', 42);
        $record = new Record('www.example.com', '192.0.2.10', 'A');

        $this->expectException(Rage4Exception::class);
        $this->expectExceptionMessage($message);

        $record->create($domain, $api);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function createErrorProvider()
    {
        return array(
            'descriptive API error' => array('invalid value', 'error: invalid value'),
            'unexpected response type' => array(null, 'Failed to create record: www.example.com'),
        );
    }

    /**
     * @return void
     */
    public function testStaticLookupsDelegateThroughDomainBehavior()
    {
        $data = $this->recordData();
        $api = $this->createMock(Rage4Api::class);
        $api->method('getDomainByName')->willReturn(array(
            'id' => 42, 'name' => 'example.com', 'type' => 0, 'owner_email' => 'hostmaster@example.com',
        ));
        $api->method('getRecords')->willReturn(array($data));
        $domain = new Domain('example.com', Domain::TYPE_DOMAIN, 'hostmaster@example.com');
        $createdDomain = new CreatedDomain('example.com', Domain::TYPE_DOMAIN, 'hostmaster@example.com', 42);

        $this->assertSame(9, Record::fromName($api, 'www.example.com', $domain, 'A')->id);
        $this->assertSame(9, Record::fromId($api, $createdDomain, 9)->id);
        $this->assertNull(Record::fromId($api, $createdDomain, 999));
    }

    /**
     * @return void
     */
    public function testCreatedRecordGetSupportsStoredAndExplicitDomains()
    {
        $data = $this->recordData();
        $api = $this->createMock(Rage4Api::class);
        $api->method('getDomain')->willReturn(array(
            'id' => 42, 'name' => 'example.com', 'type' => 0, 'owner_email' => 'hostmaster@example.com',
        ));
        $api->method('getRecords')->willReturn(array($data));
        $record = Record::fromRage4($data);
        $domain = new CreatedDomain('example.com', Domain::TYPE_DOMAIN, 'hostmaster@example.com', 42);

        $this->assertSame(9, $record->get($api, null, true)->id);
        $this->assertSame(9, $record->get($api, $domain, false)[0]->id);
    }

    /**
     * @return void
     */
    public function testCreatedRecordGetReturnsNullWhenDomainOrRecordIsMissing()
    {
        $api = $this->createMock(Rage4Api::class);
        $api->method('getDomain')->willReturn('not found');
        $withoutDomain = new CreatedRecord(9, 'www.example.com', '192.0.2.10', 'A');
        $withMissingDomain = new CreatedRecord(9, 'www.example.com', '192.0.2.10', 'A', 1, null, null, 300, 42);
        $domain = new CreatedDomain('example.com', Domain::TYPE_DOMAIN, 'hostmaster@example.com', 42);

        $this->assertNull($withoutDomain->get($api));
        $this->assertNull($withMissingDomain->get($api));
        $api->method('getRecords')->willReturn(array());
        $this->assertNull($withoutDomain->get($api, $domain));
    }

    /**
     * @return void
     */
    public function testUpdateCanApplyTemplateAndDeleteUsesRecordId()
    {
        $api = $this->createMock(Rage4Api::class);
        $api->expects($this->once())->method('updateRecord')
            ->with(9, 'new.example.com', '198.51.100.8', 20, false, '', 60, 0, null, null)
            ->willReturn(array('status' => true));
        $api->expects($this->once())->method('deleteRecord')->with(9);
        $created = new CreatedRecord(9, 'old.example.com', '192.0.2.10', 'A', 10, true, '192.0.2.11', 300, 42, 7, 1.2, 3.4);
        $template = new Record('new.example.com', '198.51.100.8', 'A', 20, false, '', 60);

        $this->assertSame(array('status' => true), $created->update($api, $template));
        $created->delete($api);

        $this->assertSame(9, $created->id);
        $this->assertSame(42, $created->domain);
        $this->assertSame('new.example.com', $created->name);
    }

    /**
     * @dataProvider equalityProvider
     *
     * @param Record $left
     * @param Record $right
     * @param bool $expected
     * @return void
     */
    public function testEqualityReflectsDnsBehavior(Record $left, Record $right, $expected)
    {
        $this->assertSame($expected, $left->equals($right));
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function equalityProvider()
    {
        $base = new Record('www.example.com', '192.0.2.10', 'A', 1, false, '', 300);

        return array(
            'same values' => array($base, clone $base, true),
            'different content' => array($base, new Record('www.example.com', '192.0.2.11', 'A', 1, false, '', 300), false),
            'coordinates ignored outside geo records' => array(
                new Record('www.example.com', '192.0.2.10', 'A', 1, false, '', 300, 7, 1.0, 2.0),
                new Record('www.example.com', '192.0.2.10', 'A', 1, false, '', 300, 0),
                false,
            ),
        );
    }
}
