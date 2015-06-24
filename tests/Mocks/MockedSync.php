<?php

use Splitice\Rage4\Sync\DefaultSync;

class MockedSync extends DefaultSync {
    protected $records;

    function __construct($records){
        $this->records = $records;
    }
    protected function get_records(CreatedDomain $domain, Rage4Api $api){
        return $this->records;
    }
}