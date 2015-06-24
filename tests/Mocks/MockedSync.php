<?php

use Splitice\Rage4\CreatedDomain;
use Splitice\Rage4\CreatedRecord;
use Splitice\Rage4\Record;
use Splitice\Rage4\Sync\SimpleSync;

class MockedSync extends SimpleSync {
    protected $records;
    public $actions = array();
    private $refreshed_records = array();

    /**
     * @param array $records
     */
    function __construct($records){
        $this->records = $records;
        $this->refreshed_records = array();
    }
    protected function get_records(CreatedDomain $domain){
        $records = array();
        foreach($this->records as $r){
            $records[$r->id] = $r;
        }
        return $records;
    }


    protected function r4_delete_record(CreatedRecord $record){
        $this->actions[] = array('delete', $record);
        foreach($this->refreshed_records as $k=>$r){
            if($r->id == $record->id){
                unset($this->refreshed_records[$k]);
            }
        }
    }

    protected function r4_update_record(CreatedRecord $record){
        $this->actions[] = array('update', $record);
        foreach($this->refreshed_records as $k=>$r){
            if($r->id == $record->id){
                $this->refreshed_records[$k] = $record;
            }
        }
    }

    protected function r4_add_record(CreatedDomain $domain, Record $record){
        $record = $record->upgradeWithId(count($this->actions));
        $this->actions[] = array('add', $record);
        $this->refreshed_records[] = $record;
        return $record;
    }

    protected function r4_refresh_records(CreatedDomain $domain)
    {
        $domain->records = $this->refreshed_records;
    }
}