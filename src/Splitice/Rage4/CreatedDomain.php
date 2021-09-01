<?php
namespace Splitice\Rage4;
use Splitice\Rage4\Sync\SimpleSync;
use Splitice\Rage4\Sync\IDomainSync;

/**
 * Class CreatedDomain
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

    private static function _match($pattern, $value){
        if(empty($pattern)){
            return $value == $pattern;
        }

        $last = strlen($pattern) - 1;
        if($last != 0 && $pattern[0] == '`'){
            return preg_match($pattern, $value);
        }

        return $pattern == $value;
    }

    /**
     * @param Rage4Api $api
     * @param $name
     * @param null $type will be interpreted as a regular expression if valid & start with "`"
     * @param bool $array
     * @param bool $cache
     * @return CreatedRecord|null|CreatedRecord[]
     */
    function get_record(Rage4Api $api, $name, $type = null, $array = false, $cache = false)
    {
        $ret = $array ? [] : null;
        $records = $this->get_records($api, $cache);
        foreach ($records as $r) {
            if ($r->name == $name && ($type === null || self::_match($type,$r->type))) {
                if($array) {
                    $ret[] = $r;
                }else{
                    return $r;
                }
            }
        }
        return $ret;
    }

    /**
     * Overload of Record::get() returning $this
     *
     * @param Rage4Api $api
     * @return CreatedDomain
     */
    function get(Rage4Api $api)
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
     * @param IDomainSync $sync
     * @param bool $doDelete
     * @return null|CreatedDomain|void
     */
    function sync(IDomainSync $sync, $doDelete = true)
    {
        $sync->sync_domain($this,$doDelete);
    }

    function add_record(Record $record, Rage4Api $api)
    {
        return $record->create($this, $api);
    }
}