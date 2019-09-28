<?php
/**
 * Created by PhpStorm.
 * User: Tioncico
 * Date: 2019/9/24 0024
 * Time: 15:46
 */

include "../vendor/autoload.php";
$server_list = [
    'tcp://172.16.253.156:9003',
];
$client = new \Redis();
$client->connect('172.16.253.156','6379');
$result = $client->hScan('b');
var_dump($result);