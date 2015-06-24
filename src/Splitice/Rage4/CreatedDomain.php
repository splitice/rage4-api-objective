<?php
namespace Splitice\Rage4;
use Splitice\Rage4\Sync\DefaultSync;
use Splitice\Rage4\Sync\IDomainSync;

/**
 * Class CreatedDomain
 * @package X4B\Rage4
 * @property CreatedRecord[] $records
 */
class CreatedDomain extends Domain
{
    /**
     * @var int
     */
    public $subnet_mask;
    /**
     * @var int
     */
    public $id;

    /**
     * @param string $name
     * @param int $type
     * @param string $email
     * @param int $id
     * @param array $records
     * @param int $subnet_mask
     * @param string|null $nsdomain
     */
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

    /**
     * Overload of Record::get() returning $this
     *
     * @return CreatedDomain
     */
    function get()
    {
        return $this;
    }

    /**
     * Refresh the cached records
     *
     * @param Rage4Api $api
     */
    function refresh_records(Rage4Api $api)
    {
        $this->records = $this->get_records($api);
    }

    /**
     * Syncronise two domains
     *
     * @param Rage4Api $api
     * @param bool $doDelete
     * @throws \Exception
     */
    function sync(Rage4Api $api, $doDelete = true, IDomainSync $sync = null)
    {
        if($sync === null) {
            $sync = new DefaultSync();
        }
        $sync->sync_domain($this,$api,$doDelete);
    }

    function add_record(Record $record, Rage4Api $api)
    {
        return $record->create($this, $api);
    }
}