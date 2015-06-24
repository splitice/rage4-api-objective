<?php
namespace Splitice\Rage4\Sync;


use Splitice\Rage4\CreatedDomain;
use Splitice\Rage4\CreatedRecord;
use Splitice\Rage4\Rage4Api;
use Splitice\Rage4\Record;

class DefaultSync implements IDomainSync {
    /**
     * @param CreatedDomain $domain
     * @param Rage4Api $api
     * @return Record[]
     */
    protected function get_records(CreatedDomain $domain, Rage4Api $api){
        $ret = array();
        foreach($domain->get_records($api, true) as $record) {
            $ret[$record->id] = $record;
        }
        return $ret;
    }

    protected function handle_delete(CreatedDomain $domain, Rage4Api $api){
        //$records: what we want
        $records = $domain->records;

        //$domain->records: what we have
        $domain->refresh_records($api);

        foreach ($domain->records as $z1) {
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

    protected function handle_records(CreatedDomain $domain, Rage4Api $api){
        $r4records = $this->get_records($domain, $api);

        foreach ($domain->records as $k => $record) {
            if ($record instanceof CreatedRecord && isset($r4records[$record->id])) {
                if (!$record->equals($r4records[$record->id])) {
                    $record->update($api);
                }
            } else if ($record instanceof Record) {
                $record2 = $this->record_search($r4records, $record);
                if (!$record2) {
                      /*  if (!$record->equals($record2)) {
                            //Update record values
                            $record2->content = $record->content;
                            $record2->ttl = $record->ttl;
                            $record2->geo = $record->geo;
                            $record2->geolat = $record->geolat;
                            $record2->geolong = $record->geolong;

                            $record2->update($api);
                        }

                        $domain->records[$k] = $record2;
                    } else {
                        $domain->records[$k] = $domain->add_record($record, $api);
                    }
                } else {*/
                    $domain->records[$k] = $domain->add_record($record, $api);
                }
            }else{
                throw new \RuntimeException("Unknown record object type added to domain");
            }
        }
    }

    function sync_domain(CreatedDomain $domain, Rage4Api $api, $doDelete = true){
        $this->handle_records($domain, $api);

        //DELETE if
        if ($doDelete) {
            $this->handle_delete($domain, $api);
        }
    }
}