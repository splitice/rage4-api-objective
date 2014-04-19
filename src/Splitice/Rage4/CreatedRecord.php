<?php
namespace SplitIce\Rage4;
use Splitice\Rage4\API;

class CreatedRecord extends Record {
	public $id;
	public $domain;
	
	public function __construct($id, $name, $content, $type, $priority = null, $failover = null, $failovercontent = null, $ttl = 3600, $domain = null, $geo = 0, $geolat=null, $geolong=null){
		parent::__construct($name, $content, $type, $priority, $failover, $failovercontent, $ttl, $geo, $geolat, $geolong);
		$this->id = $id;
		$this->domain = $domain;
	}
	
	function get(API $api, CreatedDomain $domain = null, $single = false){
		if($domain === null)
			throw new \Exception('TODO');
		
		foreach($domain->get_records($api,true) as $record){
			if($record->id == $this->id){
				if($single){
					return $record;
				}
				return array($record);
			}
		}
		
		return null;
	}
	
	function update(API $api, Record $record = null){
		if($record){
			foreach($this as $k=>$v){
				if($k != 'id' && $k != 'domain')
					$this->k = null;
			}
			foreach($record as $k=>$v)
				$this->$k = $v;
		}
		//if(\Radical\Core\Server::isCLI())
		//	echo "Updating ",$this->name,"\r\n";
		$api->updateRecord($this->id, $this->name, $this->content, $this->priority, $this->failover, $this->failovercontent, $this->ttl, $this->geo, $this->geolat, $this->geolong);
	}
	
	function delete(API $api){
		$api->deleteRecord($this->id);
	}
}