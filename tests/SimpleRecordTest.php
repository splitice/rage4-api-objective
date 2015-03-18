<?php
use Splitice\Rage4\Domain;
use Splitice\Rage4\Rage4Api;

/**
 * Created by PhpStorm.
 * User: splitice
 * Date: 6/4/14
 * Time: 2:24 PM
 */

class SimpleDomainTest extends PHPUnit_Framework_TestCase {
    const API_CLIENT = '\\Splitice\\Rage4\\IRage4ApiClient';

    function testCreateDomain(){
        //Setup
        $name = "test.com";
        $email = "email@test.com";
        $ns = "ns";
        $type = Domain::TYPE_DOMAIN;

        //Assert
        $client = $this->getMock(self::API_CLIENT);
        $client->expects($this->once())->method('executeApi')->with($this->equalTo('createregulardomain'),$this->equalTo(array('name'=>$name,'email'=>$email,'ns1'=>'ns1.'.$ns,'ns2'=>'ns2.'.$ns)));

        //Do
        $api = new Rage4Api($client);
        $domain = new Domain($name, $type, $email, array(), $ns);
        $domain->create($api);
    }
} 