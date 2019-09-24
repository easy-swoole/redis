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
//    $client->sendCommand(['del',"listA"]);
//    $recv = $client->recv();
//    $client->sendCommand(['sadd',"listA",1]);
//    $recv = $client->recv();
//    $client->sendCommand(['sadd',"listA",'a']);
//    $recv = $client->recv();
//    $client->sendCommand(['sadd',"listA",null]);
//    $recv = $client->recv();
//    $client->sendCommand(['sadd',"listA","1\r\n 2\r\n a\r\nf"]);
//    $recv = $client->recv();
//    $client->sendCommand(['sadd',"listA"," "]);
//    $recv = $client->recv();

    $client->sendCommand(['smembers',"listA"]);
    $recv = $client->recv();
    var_dump($recv);


});