<?php
namespace SplitIce\Rage4;

use Splitice\Rage4\API;

class CreatedDomain extends Domain {
	public $subnet_mask;
	
	function __construct($name, $type, $email, $id, $records = array(), $subnet_mask = 0, $nsdomain = null){
		$this->id = $id;
		$this->subnet_mask = $subnet_mask;
		parent::__construct($name, $type, $email, $records, $nsdomain);
	}
	private $record_cache = null;
	function get_records(API $api, $cache = false){
		if($this->record_cache !== null){
			return $this->record_cache;
		}
		$records = array();
		$ret = $api->getRecords($this->id);
		
		foreach($ret as $r){
			$records[] = CreatedRecord::fromRage4($r);
		}
		$this->record_cache = $records;
		return $records;
	}
	function get_record(API $api, $name, $type = null, $cache = false){
		$records = $this->get_records($api,$cache);
		foreach($records as $r)
			if($r->name == $name && ($type === null || $type == $r->type))
				return $r;
	}
	function get(){
		return $this;
	}
	function RefreshRecords(API $api){
		$this->records = $this->get_records($api);
	}
	function sync(API $api, $doDelete = true){
		$this->sync_zones($api, $doDelete);
	}
	function sync_zones(API $api, $doDelete = true){
		foreach($this->records as $k=>$record){
			if($record instanceof CreatedRecord){
				$record2 = $record->get($api, $this, true);
				if($record2){
					if($record2->content != $record->content || $record2->ttl != $record->ttl){
						$record->update($api);
					}
				}
			}else if($record instanceof Record){
				$records2 = $record->get($api, $this);
				if($records2){
					$record2 = null;
					foreach($records2 as $r){
						if($r->content == $record->content){
							$record2 = $r;
							break;
						}
					}
					if($record2){
						if($record2->ttl != $record->ttl || $record2->geo != $record->geo || $record2->geolat != $record->geolat || $record2->geolong != $record->geolong){
							$record2->content = $record->content;
							$record2->ttl = $record->ttl;
							$record2->geo = $record->geo;
							$record2->geolat = $record->geolat;
							$record2->geolong = $record->geolong;
							
							$record2->update($api);
						}
							
						$this->records[] = $record2;
					}else{
						$this->add_record($record, $api);
					}
				}else{
					$this->add_record($record, $api);
				}
				unset($this->records[$k]);
			}
		}
		
		//DELETE if 
		if($doDelete){
			//$records: what we want
			$records = $this->records;
			
			//$this->records: what we have
			$this->RefreshRecords($api);
			foreach($this->records as $z1){
				if($z1->type != 'CNAME' && $z1->type != 'A'){
					continue;
				}
				
				$found = false;
				foreach($records as $z2){
					if($z2->id == $z1->id){
						$found = true;
						break;
					}
				}
				if(!$found){
					$z1->delete($api);
				}
			}
		}
	}
	function add_record(Record $records, API $api){
		return $records->create($this, $api);
	}
}