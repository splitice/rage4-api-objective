<?php
namespace Splitice\Rage4;

/**
 * Class CreatedDomain
 * @package X4B\Rage4
 * @property CreatedRecord[] $records
 */
class CreatedDomain extends Domain
{
    public $subnet_mask;
    /**
     * @var integer
     */
    public $id;

    function __construct($name, $type, $email, $id, $records = array(), $subnet_mask = 0, $nsdomain = null)
    {
        $this->id = $id;
        $this->subnet_mask = $subnet_mask;
        parent::__construct($name, $type, $email, $records, $nsdomain);
    }

    /**
     * Delete domain at Rage4.com
     *
     * @param Rage4Api $api
     */
    function delete(Rage4Api $api)
    {
        $api->deleteDomain($this->id);
    }

    private $record_cache = null;

    /**
     * @param Rage4Api $api
     * @param bool $cache
     * @return CreatedRecord[]
     */
    function get_records(Rage4Api $api, $cache = false)
    {
        if ($cache && $this->record_cache !== null) {
            return $this->record_cache;
        }
        $records = array();
        $ret = $api->getRecords($this->id);

        foreach ($ret as $r) {
            $records[] = CreatedRecord::fromRage4($r);
        }
        if ($cache)
            $this->record_cache = $records;
        return $records;
    }

    /**
     * @param Rage4Api $api
     * @param $name
     * @param null $type
     * @param bool $cache
     * @return CreatedRecord|null
     */
    function get_record(Rage4Api $api, $name, $type = null, $cache = false)
    {
        $records = $this->get_records($api, $cache);
        foreach ($records as $r)
            if ($r->name == $name && ($type === null || $type == $r->type))
                return $r;
        return null;
    }

    function get()
    {
        return $this;
    }

    function refresh_records(Rage4Api $api)
    {
        $this->records = $this->get_records($api);
    }

    function sync(Rage4Api $api, $doDelete = true)
    {
        $this->sync_zones($api, $doDelete);
    }

    function sync_zones(Rage4Api $api, $doDelete = true)
    {
        foreach ($this->records as $k => $record) {
            if ($record instanceof CreatedRecord) {
                $record2 = $record->get($api, $this, true);
                if ($record2) {
                    if ($record2->content != $record->content || $record2->ttl != $record->ttl) {
                        $record->update($api);
                    }
                }
            } else if ($record instanceof Record) {
                $records2 = $record->get($api, $this);
                if ($records2) {
                    $record2 = null;
                    foreach ($records2 as $r) {
                        if ($r->content == $record->content) {
                            $record2 = $r;
                            break;
                        }
                    }
                    if ($record2) {
                        if ($record2->ttl != $record->ttl || $record2->geo != $record->geo || ($record2->geolat != $record->geolat && $record->geolat !== null) || ($record2->geolong != $record->geolong && $record->geolong !== null)) {
                            $record2->content = $record->content;
                            $record2->ttl = $record->ttl;
                            $record2->geo = $record->geo;
                            $record2->geolat = $record->geolat;
                            $record2->geolong = $record->geolong;

                            $record2->update($api);
                        }

                        $this->records[$k] = $record2;
                    } else {
                        $this->records[$k] = $this->add_record($record, $api);
                    }
                } else {
                    $this->records[$k] = $this->add_record($record, $api);
                }
            }
        }

        //DELETE if
        if ($doDelete) {
            //$records: what we want
            $records = $this->records;

            //$this->records: what we have
            $this->refresh_records($api);

            foreach ($this->records as $z1) {
                //TODO: logic behind this, and support for additional NS records
                if ($z1->type == 'SOA' && $z1->type == 'NS') {
                    continue;
                }

                $found = false;
                foreach ($records as $z2) {
                    if ($z2->id == $z1->id) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $z1->delete($api);
                }
            }
        }
    }

    function add_record(Record $record, Rage4Api $api)
    {
        return $record->create($this, $api);
    }
}