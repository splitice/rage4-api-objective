<?php
namespace SplitIce\Rage4;

class CreatedRecord extends Record
{
    public $id;
    public $domain;

    public function __construct($id, $name, $content, $type, $priority = null, $failover = null, $failovercontent = null, $ttl = 3600, $domain = null, $geo = 0, $geolat = null, $geolong = null)
    {
        parent::__construct($name, $content, $type, $priority, $failover, $failovercontent, $ttl, $geo, $geolat, $geolong);
        $this->id = $id;
        $this->domain = $domain;
    }

    function get(Rage4Api $api, CreatedDomain $domain = null, $single = false)
    {
        if ($domain === null)
            throw new \Exception('TODO');

        foreach ($domain->get_records($api, true) as $record) {
            if ($record->id == $this->id) {
                if ($single) {
                    return $record;
                }
                return array($record);
            }
        }

        return null;
    }

    function update(Rage4Api $api, Record $record = null)
    {
        if ($record) {
            foreach (get_object_vars($this) as $k => $v) {
                if ($k != 'id' && $k != 'domain')
                    $this->$k = null;
            }
            foreach (get_object_vars($record) as $k => $v)
                $this->$k = $v;
        }
        $api->updateRecord($this->id, $this->name, $this->content, $this->priority, $this->failover, $this->failovercontent, $this->ttl, $this->geo, $this->geolat, $this->geolong, $this->geolock);
    }

    function delete(Rage4Api $api)
    {
        $api->deleteRecord($this->id);
    }
}