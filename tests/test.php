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
    $client->sendCommand(['set','a',1]);
    $recv = $client->recv();

    $client->sendCommand(['incr',"a"]);
    $recv = $client->recv();
    var_dump($recv);
});