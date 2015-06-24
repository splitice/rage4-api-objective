<?php
namespace Splitice\Rage4;

class Record
{
    const DEFAULT_PRIORITY = 1;

    public $name;
    public $content;
    public $type;
    public $priority = self::DEFAULT_PRIORITY;
    public $failover = null;
    public $failovercontent = null;
    public $ttl = 3600;
    public $geo = 0;
    public $geolat = null;
    public $geolong = null;
    public $geolock;

    public function __construct($name, $content, $type, $priority = self::DEFAULT_PRIORITY, $failover = null, $failovercontent = null, $ttl = 3600, $geo = 0, $geolat = null, $geolong = null, $geolock = false)
    {
        $this->name = $name;
        $this->content = (string)$content;
        $this->type = $type;
        $this->priority = $priority === null ? 1 : $priority;
        $this->failover = $failover;
        $this->failovercontent = $failovercontent;
        $this->ttl = $ttl;
        $this->geo = $geo;
        if($geo) {
            $this->geolat = $geolat;
            $this->geolong = $geolong;
        }
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

    /**
     * Given ID instantiate a CreatedRecord instance from this record
     *
     * @param int $id Rage4 record id
     * @return CreatedRecord
     */
    private function _created($id)
    {
        return new CreatedRecord($id, $this->name, $this->content, $this->type, $this->priority, $this->failover, $this->failovercontent, $this->ttl, $this->geo, $this->geolat, $this->geolong);
    }

    /**
     * Make into a created record object given an ID manually.
     *
     * @param $id
     * @return CreatedRecord
     */
    function upgradeWithId($id){
        return $this->_created($id);
    }

    /**
     * @param CreatedDomain $domain
     * @param Rage4Api $api
     * @return CreatedRecord
     * @throws Rage4Exception
     */
    function create(CreatedDomain $domain, Rage4Api $api)
    {
        $ret = $api->createRecord($domain->id, $this->name, $this->content, $this->type, $this->priority, $this->failover, $this->failovercontent, $this->ttl, $this->geo, $this->geolat, $this->geolong);
        $id = null;
        if (is_string($ret)){
            throw new Rage4Exception("Failed to create record: ".$this->name.' error: '.$ret);
        }
        if (!is_array($ret)) {
            throw new Rage4Exception("Failed to create record: ".$this->name);
        }
        $id = $ret['id'];
        return $this->_created($id);
    }

    /**
     * Create a record instance from Rage4 record data returned.
     *
     * @param $a Rage4 structured record data
     * @return CreatedRecord
     */
    static function fromRage4($a)
    {
        return new CreatedRecord($a['id'], $a['name'], $a['content'], $a['type'], $a['priority'], $a['failover_enabled'], $a['failover_content'],
            $a['ttl'], $a['domain_id'], $a['geo_region_id'], $a['geo_lat'], $a['geo_long']);
    }

    /**
     * Get a record given a name and a Domain instance.
     *
     * @param Rage4Api $api
     * @param string $name
     * @param Domain $domain
     * @param int|null $type
     * @return null|CreatedRecord
     */
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

    /**
     * Compare two Record instances
     *
     * @param Record $record
     * @return bool
     */
    function equals(Record $record){
        return $this->name == $record->name && $this->content == $record->content && $this->type == $record->type && $this->priority == $record->priority && $this->failover == $record->failover &&  $this->failovercontent == $record->failovercontent &&
        $this->ttl == $record->ttl && $this->geo == $record->geo && ($this->geolat == $record->geolat || !$record->geo) && ($this->geolong == $record->geolong || !$record->geo);
    }
}