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

        //What we are wanting
        $cd = new CreatedDomain($name, $type, $email, 1, array(), $ns);
        $cd->records[] = new Record($name,"1.1.1.1",'A');

        //What we have
        $records = array();

        $sync = new MockedSync($records);

        $cd->sync($sync,true);

        $this->assertEquals(1, count($sync->actions));
        $this->assertEquals('add', $sync->actions[0][0]);
    }

    function testSimpleNoopSync(){
        //Setup
        $name = "test.com";
        $email = "email@test.com";
        $ns = "ns";
        $type = Domain::TYPE_DOMAIN;

        //What we are wanting
        $cd = new CreatedDomain($name, $type, $email, 1, array(), $ns);
        $cd->records[] = new Record($name,"1.1.1.1",'A');

        //What we have
        $records = array();
        $records[] = new CreatedRecord(1, $name, "1.1.1.1", 'A');

        $sync = new MockedSync($records);

        $cd->sync($sync,true);

        $this->assertEquals(0, count($sync->actions));
    }

    function testSimpleDeleteSync(){
        //Setup
        $name = "test.com";
        $email = "email@test.com";
        $ns = "ns";
        $type = Domain::TYPE_DOMAIN;

        //What we are wanting
        $cd = new CreatedDomain($name, $type, $email, 1, array(), $ns);

        //What we have
        $records = array();
        $records[] = new CreatedRecord(1, $name, "1.1.1.1", 'A');

        $sync = new MockedSync($records);

        $cd->sync($sync,true);

        $this->assertEquals(1, count($sync->actions));
        $this->assertEquals('delete', $sync->actions[0][0]);
    }

    function testSimpleUpdateSync(){
        //Setup
        $name = "test.com";
        $email = "email@test.com";
        $ns = "ns";
        $type = Domain::TYPE_DOMAIN;

        //What we are wanting
        $cd = new CreatedDomain($name, $type, $email, 1, array(), $ns);
        $cd->records[] = new Record($name, "1.1.1.2", 'A');

        //What we have
        $records = array();
        $records[] = new CreatedRecord(1, $name, "1.1.1.1", 'A');

        $sync = new MockedSync($records);

        $cd->sync($sync,true);

        $this->assertEquals(2, count($sync->actions));
        $this->assertEquals('add', $sync->actions[0][0]);
        $this->assertEquals('1.1.1.2', $sync->actions[0][1]->content);
        $this->assertEquals('delete', $sync->actions[1][0]);
        $this->assertEquals('1.1.1.1', $sync->actions[1][1]->content);
    }

    function testSimpleAddWithPersistSync(){
        //Setup
        $name = "test.com";
        $email = "email@test.com";
        $ns = "ns";
        $type = Domain::TYPE_DOMAIN;

        //What we are wanting
        $cd = new CreatedDomain($name, $type, $email, 1, array(), $ns);
        $cd->records[] = new Record($name, "1.1.1.2", 'A');
        $cd->records[] = new Record($name, "mailA.com", 'MX');

        //What we have
        $records = array();
        $records[] = new CreatedRecord(2, $name, "mailA.com", 'MX');

        $sync = new MockedSync($records);

        $cd->sync($sync,true);

        $this->assertEquals(1, count($sync->actions));
        $this->assertEquals('add', $sync->actions[0][0]);
        $this->assertEquals('1.1.1.2', $sync->actions[0][1]->content);
    }


    function testSimpleUpdateWithPersistSync(){
        //Setup
        $name = "test.com";
        $email = "email@test.com";
        $ns = "ns";
        $type = Domain::TYPE_DOMAIN;

        //What we are wanting
        $cd = new CreatedDomain($name, $type, $email, 1, array(), $ns);
        $cd->records[] = new Record($name, "1.1.1.2", 'A');
        $cd->records[] = new Record($name, "mailA.com", 'MX');

        //What we have
        $records = array();
        $records[] = new CreatedRecord(1, $name, "1.1.1.1", 'A');
        $records[] = new CreatedRecord(2, $name, "mailA.com", 'MX');

        $sync = new MockedSync($records);

        $cd->sync($sync,true);

        $this->assertEquals(2, count($sync->actions));
        $this->assertEquals('add', $sync->actions[0][0]);
        $this->assertEquals('1.1.1.2', $sync->actions[0][1]->content);
        $this->assertEquals('delete', $sync->actions[1][0]);
        $this->assertEquals('1.1.1.1', $sync->actions[1][1]->content);
    }


    function testSimpleDeleteWithPersistSync(){
        //Setup
        $name = "test.com";
        $email = "email@test.com";
        $ns = "ns";
        $type = Domain::TYPE_DOMAIN;

        //What we are wanting
        $cd = new CreatedDomain($name, $type, $email, 1, array(), $ns);
        $cd->records[] = new Record($name, "mailA.com", 'MX');

        //What we have
        $records = array();
        $records[] = new CreatedRecord(1, $name, "1.1.1.1", 'A');
        $records[] = new CreatedRecord(2, $name, "mailA.com", 'MX');

        $sync = new MockedSync($records);

        $cd->sync($sync,true);

        $this->assertEquals(1, count($sync->actions));
        $this->assertEquals('delete', $sync->actions[0][0]);
        $this->assertEquals('1.1.1.1', $sync->actions[0][1]->content);
    }
} 