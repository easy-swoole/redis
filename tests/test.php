<?php
/**
 * Created by PhpStorm.
 * User: Tioncico
 * Date: 2019/9/24 0024
 * Time: 15:46
 */

include "../vendor/autoload.php";

go(function (){

    $config = new \EasySwoole\Redis\Config\RedisConfig();

    $redis = new \EasySwoole\Redis\Redis($config);

    var_dump($redis->set('a',123));
    $result = $redis->get('a');
    var_dump($result);

});