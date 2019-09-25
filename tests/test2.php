<?php
/**
 * Created by PhpStorm.
 * User: Tioncico
 * Date: 2019/9/24 0024
 * Time: 15:46
 */

include "../vendor/autoload.php";
go(function (){

    $client = new \EasySwoole\Redis\ClusterClient([['172.16.253.156',9001]],3);

    $client->sendCommand(['incr', 'b']);
    $recv = $client->recv();
    var_dump($recv);
//    $recv = $client->recv();
   //    $client->sendCommand(['georadius','geoa',$recv->getData()[0][0],$recv->getData()[0][1],'100','km','desc']);
//    $recv = $client->recv();
//
//    var_dump($recv);
});