<?php
namespace Splitice\Rage4;

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

    /**
     * Get Created record from Rage4.com (overload)
     * TODO: remove / refactor
     *
     * @param Rage4Api $api
     * @param CreatedDomain $domain
     * @param bool $single
     * @return array|null|CreatedRecord|CreatedRecord[]|Record
     */
    function get(Rage4Api $api, CreatedDomain $domain = null, $single = false)
    {
        if ($domain === null)
            $domain = CreatedDomain::fromId($api, $this->domain);

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

    /**
     * Update record at Rage4.com
     *
     * @param Rage4Api $api
     * @param Record|null $record optionally, a record to template this record off.
     */
    function update(Rage4Api $api, Record $record = null)
    {
        //Set the value of this object to record, if we are updating with a record template
        if ($record) {
            foreach (get_object_vars($this) as $k => $v) {
                if ($k != 'id' && $k != 'domain')
                    $this->$k = null;
            }
            foreach (get_object_vars($record) as $k => $v)
                $this->$k = $v;
        }

        //Perform API update call
        $api->updateRecord($this->id, $this->name, $this->content, $this->priority, $this->failover, $this->failovercontent, $this->ttl, $this->geo, $this->geolat, $this->geolong);
    }

    /**
     * Delete record at Rage4.com
     *
     * @param Rage4Api $api
     */
    function delete(Rage4Api $api)
    {
        $api->deleteRecord($this->id);
    }
}