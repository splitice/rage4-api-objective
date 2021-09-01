<?php
namespace Splitice\Rage4\Sync;


use Splitice\Rage4\CreatedDomain;
use Splitice\Rage4\CreatedRecord;
use Splitice\Rage4\Rage4Api;
use Splitice\Rage4\Record;

class SimpleSync implements IDomainSync {
    private $api;
    private $transactional = false;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    function __construct(Rage4Api $api, LoggerInterface $logger = null){
        $this->api = $api;
        $this->logger = $logger;
    }

    protected function r4_delete_record(CreatedRecord $record){
        if($this->logger) {
            $this->logger->info("Deleting ". $record->name. ":". $record->content);
        }
        return $record->delete($this->api);
    }

    protected function r4_update_record(CreatedRecord $record){
        if($this->logger) {
            $this->logger->info("Updating " . $record->name);
        }
        return $record->update($this->api);
    }

    protected function r4_add_record(CreatedDomain $domain, Record $record){
        if($this->logger) {
            $this->logger->info("Adding ". $record->name);
        }
        return $domain->add_record($record, $this->api);
    }

    protected function r4_refresh_records(CreatedDomain $domain)
    {
        return $domain->refresh_records($this->api);
    }

    /**
     * @param CreatedDomain $domain
     * @return Record[]
     */
    protected function get_records(CreatedDomain $domain){
        $ret = array();
        foreach($domain->get_records($this->api, true) as $record) {
            $ret[$record->id] = $record;
        }
        return $ret;
    }

    protected function handle_delete(CreatedDomain $domain){
        //$records: what we want
        $records = $domain->records;

        //$domain->records: what we have
        $this->r4_refresh_records($domain,$this->api);

        foreach ($this->get_records($domain) as $z1) {
            //TODO: logic behind this, and support for additional NS records
            if ($z1->type == 'SOA' || $z1->type == 'NS') {
                continue;
            }

            $found = false;
            foreach ($records as $z2) {
                if($z1 instanceof CreatedRecord && $z1 instanceof CreatedRecord) {
                    if ($z2->id == $z1->id) {
                        $found = true;
                        break;
                    }
                }elseif($this->transactional && $record instanceof Record){

                }else{
                    throw new \RuntimeException("There was a problem syncing deletes, all records should be created at this point.");
                }
            }
            if (!$found) {
                $this->r4_delete_record($z1);
            }
        }
    }

    /**
     * @param Record[] $r4records
     * @param Record $record
     * @return mixed
     */
    protected function record_search($r4records, Record $record){
        foreach($r4records as $r2){
            if($r2->equals($record)){
                return $r2;
            }
        }
    }

    protected function handle_records(CreatedDomain $domain){
        $r4records = $this->get_records($domain);

        foreach ($domain->records as $k => $record) {
            if ($record instanceof CreatedRecord && isset($r4records[$record->id])) {
                if (!$record->equals($r4records[$record->id])) {
                    $this->r4_update_record($record);
                }
            } else if ($record instanceof Record) {
                $record2 = $this->record_search($r4records, $record);
                if (!$record2) {
                    $domain->records[$k] = $this->r4_add_record($domain, $record);
                }else{
                    $domain->records[$k] = $record2;
                }
            }else{
                throw new \RuntimeException("Unknown record object type added to domain");
            }
        }
    }

    function sync_domain(CreatedDomain $domain, $doDelete = true){
        $this->handle_records($domain);

        //DELETE if
        if ($doDelete) {
            $this->handle_delete($domain);
        }
    }
}