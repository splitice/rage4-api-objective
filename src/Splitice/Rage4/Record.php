<?php
namespace SplitIce\Rage4;

use Splitice\Rage4\API;
use Splitice\Rage4\Rage4Exception;
class Record {
	public $name;
	public $content;
	public $type;
	public $priority = null;
	public $failover = null;
	public $failovercontent = null;
	public $ttl = 3600;
	public $geo = 0;
	public $geolat = null;
	public $geolong = null;
	
	public function __construct($name, $content, $type, $priority = null, $failover = null, $failovercontent = null, $ttl = 3600, $geo=0, $geolat=null, $geolong=null){
		$this->name = $name;
		$this->content = (string)$content;
		$this->type = $type;
		$this->priority = $priority;
		$this->failover = $failover;
		$this->failovercontent = $failovercontent;
		$this->ttl = $ttl;
		$this->geo = $geo;
		$this->geolat = $geolat;
		$this->geolong = $geolong;
	}
	function get(API $api, CreatedDomain $domain, $single = false){
		$found = array();
		foreach($domain->get_records($api,true) as $record){
			if($record->name == $this->name && $record->type == $this->type)
				$found[] = $record;
		}

		if($single){
			if(count($found)!=1){
				throw new \Exception("More than one record found!");
			}
			return $found[0];
		}
		return $found;
	}
	
	private function _created($id){
		return new CreatedRecord($id, $this->name, $this->content, $this->type, $this->priority, $this->failover, $this->failovercontent, $this->ttl, $this->geo, $this->geolat, $this->geolong);
	}
	
	function create(CreatedDomain $domain, API $api){
		$ret = $api->createRecord($domain->id, $this->name, $this->content, $this->type, $this->priority, $this->failover, $this->failovercontent, $this->ttl, $this->geo, $this->geolat, $this->geolong);
		$id = null;
		if(is_array($ret)){
			$id = $ret['id'];
		}elseif(is_string($ret)){
			if(preg_match('`Record added with id ([0-9]+)`', $ret, $m)){
				$id = $m[1];
			}else{
				throw new Rage4Exception($ret);
			}
		}
		if($id)
			return $this->_created($id);
	}
	
	function update($id, API $api){
		return $this->_created($id)->update($api);
	}
	
	static function fromRage4($a){
		$locked = $a['geo_lock'];
		return new CreatedRecord($a['id'],$a['name'],$a['content'],$a['type'],$a['priority'],$a['failover_enabled'],$a['failover_content'],
									$a['ttl'],$a['domain_id'], $a['geo_region_id'], $locked?$a['geo_lat']:null, $locked?$a['geo_long']:null);
	}
	
	static function fromName(API $api, $name, Domain $domain, $type = null){
		return $domain->get($api)->get_record($api, $name, $type);
	}
	
	static function fromId(API $api, CreatedDomain $domain, $id){
		foreach($domain->get_records($api) as $record){
			if($record->id == $id)
				return $record;
		}
	}
}