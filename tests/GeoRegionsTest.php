<?php

use PHPUnit\Framework\TestCase;
use Splitice\Rage4\GeoRegions;
use Splitice\Rage4\Rage4Api;
use Splitice\Rage4\Rage4Exception;

final class GeoRegionsTest extends TestCase
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        $property = new ReflectionProperty(GeoRegions::class, 'cache');
        $property->setAccessible(true);
        $property->setValue(null, null);
    }

    /**
     * @return void
     */
    public function testRegionListMapsValuesToIntegersAndCachesResult()
    {
        $api = $this->createMock(Rage4Api::class);
        $api->expects($this->once())->method('getGeoRegions')->willReturn(array(
            array('name' => 'Worldwide', 'value' => '0'),
            array('name' => 'Europe', 'value' => '7'),
        ));

        $first = GeoRegions::RegionList($api);
        $second = GeoRegions::RegionList($api);

        $this->assertSame(array('Worldwide' => 0, 'Europe' => 7), $first);
        $this->assertSame($first, $second);
    }

    /**
     * @return void
     */
    public function testRegionListCanRefreshCachedData()
    {
        $api = $this->createMock(Rage4Api::class);
        $api->expects($this->exactly(2))->method('getGeoRegions')
            ->willReturnOnConsecutiveCalls(
                array(array('name' => 'Europe', 'value' => '7')),
                array(array('name' => 'North America', 'value' => '8'))
            );

        GeoRegions::RegionList($api);

        $this->assertSame(array('North America' => 8), GeoRegions::RegionList($api, false));
    }

    /**
     * @return void
     */
    public function testRegionListRejectsApiErrors()
    {
        $api = $this->createMock(Rage4Api::class);
        $api->method('getGeoRegions')->willReturn('authentication failed');

        $this->expectException(Rage4Exception::class);
        $this->expectExceptionMessage('authentication failed');

        GeoRegions::RegionList($api);
    }

    /**
     * @return void
     */
    public function testCountryZonesHaveUniqueIsoCodesAndCompleteGeography()
    {
        $zones = GeoRegions::GetCountryZones();
        $isoCodes = array();

        foreach ($zones as $zone) {
            $this->assertSame(array('ISO', 'Name', 'Region', 'Subregion'), array_keys($zone));
            $this->assertMatchesRegularExpression('/^[A-Z]{2}$/', $zone['ISO']);
            $this->assertNotSame('', $zone['Name']);
            $this->assertNotSame('', $zone['Region']);
            $this->assertNotSame('', $zone['Subregion']);
            $isoCodes[] = $zone['ISO'];
        }

        $this->assertGreaterThan(200, count($zones));
        $this->assertSame($isoCodes, array_values(array_unique($isoCodes)));
    }

    /**
     * @return void
     */
    public function testFederalRegionsPartitionRealisticStateNames()
    {
        $regions = GeoRegions::GetUSFederalRegionStates();
        $states = array();

        foreach ($regions as $name => $members) {
            $this->assertMatchesRegularExpression('/^USRegion(?:10|[1-9])$/', $name);
            $this->assertNotEmpty($members);
            foreach ($members as $state) {
                $this->assertIsString($state);
                $this->assertNotSame('', $state);
                $states[] = $state;
            }
        }

        $this->assertSame($states, array_values(array_unique($states)));
        $this->assertContains('California', $states);
        $this->assertContains('New York', $states);
    }
}
