<?php
/**
 * Created by PhpStorm.
 * User: Tioncico
 * Date: 2019/9/24 0024
 * Time: 15:46
 */

include "../vendor/autoload.php";
go(function (){
    $serverList =[
        ['172.16.253.156',9001],
        ['172.16.253.156',9002],
        ['172.16.253.156',9003],
        ['172.16.253.156',9004],
        ['172.16.253.156',9005],
        ['172.16.253.156',9006],
    ];
    $client = new \EasySwoole\Redis\ClusterClient($serverList,3);

    $client->sendCommand(['incr', 'b']);
    $recv = $client->recv();
    var_dump($recv);
//    $recv = $client->recv();
//    $client = new \EasySwoole\Redis\Client('172.16.253.156',9003,3);
//    $result = $client->connect();
//    $client->sendCommand(['cluster', 'nodes']);
//    $recv = $client->recv();
//    echo ($recv->getData());










   //    $client->sendCommand(['georadius','geoa',$recv->getData()[0][0],$recv->getData()[0][1],'100','km','desc']);
//    $recv = $client->recv();
//
//    var_dump($recv);
});