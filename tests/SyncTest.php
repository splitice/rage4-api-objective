<?php
use Splitice\Rage4\CreatedDomain;
use Splitice\Rage4\CreatedRecord;
use Splitice\Rage4\Domain;
use Splitice\Rage4\Rage4Api;
use Splitice\Rage4\Record;

include_once __DIR__.'/Mocks/MockedSync.php';

class SyncTest extends PHPUnit_Framework_TestCase {
    function testSimpleAddSync(){
        //Setup
        $name = "test.com";
        $email = "email@test.com";
        $ns = "ns";
        $type = Domain::TYPE_DOMAIN;

        $cd = new CreatedDomain($name, $type, $email, 1, array(), $ns);
        $cd->records[] = new Record($name,"1.1.1.1",'A');
        $records = array();


        $client = $this->getMock(Rage4Api::class);
        $client->expects($this->once())->method('createRecord')->with($this->equalTo(1),$this->equalTo($name),$this->equalTo("1.1.1.1"),'A');

        $sync = new MockedSync($records);

        $cd->sync($client, true, $sync);
    }

    function testSimpleNoopSync(){
        //Setup
        $name = "test.com";
        $email = "email@test.com";
        $ns = "ns";
        $type = Domain::TYPE_DOMAIN;

        $cd = new CreatedDomain($name, $type, $email, 1, array(), $ns);
        $cd->records[] = new Record($name,"1.1.1.1",'A');


        $client = $this->getMock(Rage4Api::class);
        $client->expects($this->never())->method('createRecord');
        $client->expects($this->never())->method('deleteRecord')
        $client->expects($this->never())->method('updateRecord');

        $records = array();
        $records[] = new CreatedRecord(1, $name, "1.1.1.1", 'A');

        $sync = new MockedSync($records);

        $cd->sync($client, true, $sync);
    }
} 