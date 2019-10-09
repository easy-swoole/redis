<?php

defined("REDIS_HOST") ?: define('REDIS_HOST', '127.0.0.1');
defined("REDIS_PORT") ?: define('REDIS_PORT', 6379);
defined("REDIS_AUTH") ?: define('REDIS_AUTH', 'easyswoole');
defined("REDIS_CLUSTER_SERVER_LIST") ?: define('REDIS_CLUSTER_SERVER_LIST',
    [
        ['172.16.253.156', 9001],
        ['172.16.253.156', 9002],
        ['172.16.253.156', 9003],
        ['172.16.253.156', 9004],
    ]);
defined("REDIS_CLUSTER_AUTH") ?: define('REDIS_CLUSTER_AUTH', '');
