<?php
namespace Splitice\Rage4;

class Record
{
    public $name;
    public $content;
    public $type;
    public $priority = null;
    public $failover = null;
    public $failovercontent = null;
    public $ttl = 3600;
    public $geo = 0;
    public $geolat = null;
    public $geolong = null;
    public $geolock;

    public function __construct($name, $content, $type, $priority = null, $failover = null, $failovercontent = null, $ttl = 3600, $geo = 0, $geolat = null, $geolong = null, $geolock = false)
    {
        $this->name = $name;
        $this->content = (string)$content;
        $this->type = $type;
        $this->priority = $priority;
        $this->failover = $failover;
        $this->failovercontent = $failovercontent;
        $this->ttl = $ttl;
        $this->geo = $geo;
        $this->geolat = $geolat;
        $this->geolong = $geolong;
        $this->geolock = $geolock;
    }

    /**
     * @param Rage4Api $api
     * @param CreatedDomain $domain
     * @param bool $single
     * @return CreatedRecord[]|Record
     * @throws \Exception
     */
    function get(Rage4Api $api, CreatedDomain $domain, $single = false)
    {
        $found = array();
        foreach ($domain->get_records($api, true) as $record) {
            if ($record->name == $this->name && $record->type == $this->type)
                $found[] = $record;
        }

        if ($single) {
            if (count($found) != 1) {
                throw new \Exception("More than one record found!");
            }
            return $found[0];
        }
        return $found;
    }

    private function _created($id)
    {
        return new CreatedRecord($id, $this->name, $this->content, $this->type, $this->priority, $this->failover, $this->failovercontent, $this->ttl, $this->geo, $this->geolat, $this->geolong);
    }

    function create(CreatedDomain $domain, Rage4Api $api)
    {
        $ret = $api->createRecord($domain->id, $this->name, $this->content, $this->type, $this->priority, $this->failover, $this->failovercontent, $this->ttl, $this->geo, $this->geolat, $this->geolong);
        $id = null;
        if (is_array($ret)) {
            $id = $ret['id'];
            return $this->_created($id);
        }
        return null;
    }

    function update($id, Rage4Api $api)
    {
        $this->_created($id)->update($api);
    }

    static function fromRage4($a)
    {
        return new CreatedRecord($a['id'], $a['name'], $a['content'], $a['type'], $a['priority'], $a['failover_enabled'], $a['failover_content'],
            $a['ttl'], $a['domain_id'], $a['geo_region_id'], $a['geo_lat'], $a['geo_long']);
    }


    static function fromName(Rage4Api $api, $name, Domain $domain, $type = null)
    {
        return $domain->get($api)->get_record($api, $name, $type);
    }

    /**
     * @param Rage4Api $api
     * @param CreatedDomain $domain
     * @param $id
     * @return CreatedRecord|null
     */
    static function fromId(Rage4Api $api, CreatedDomain $domain, $id)
    {
        foreach ($domain->get_records($api) as $record) {
            if ($record->id == $id)
                return $record;
        }
        return null;
    }
}