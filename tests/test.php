<?php
/**
 * Created by PhpStorm.
 * User: Tioncico
 * Date: 2019/9/24 0024
 * Time: 15:46
 */

include "../vendor/autoload.php";
$server_list = [
    'tcp://172.16.253.156:9001',
];
$client = new Predis\Client($server_list, array('cluster' => 'predis'));

$client->set('foo', 'bar');
$value = $client->get('foo');
var_dump($value);