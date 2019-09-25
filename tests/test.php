<?php
/**
 * Created by PhpStorm.
 * User: Tioncico
 * Date: 2019/9/24 0024
 * Time: 15:46
 */

include "../vendor/autoload.php";

go(function (){
    $client = new \EasySwoole\Redis\Client('127.0.0.1',6379);
    $client->connect();
//    $client->sendCommand(['del','geoa']);
//    $recv = $client->recv();
//
//    $client->sendCommand(['geoadd',"geoa",'118.6197800000','24.88849','user1','118.6197800000','24.88859','user2','114.8197800000','25.88849','user3','118.8197800000','22.88849','user4']);
//    $recv = $client->recv();

    $client->sendCommand(['geopos','geoa','user1','user2']);
    $recv = $client->recv();
//    var_dump($recv);
    $client->sendCommand(['georadius','geoa',$recv->getData()[0][0],$recv->getData()[0][1],'100','m','desc']);
    $recv = $client->recv();

    var_dump($recv);
});