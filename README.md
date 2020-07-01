# Redis客户端

## 单元测试
测试前记得修改phpunit.php修改配置
```
./vendor/bin/co-phpunit tests
```
## redis详细文档
http://www.easyswoole.com/Components/Redis/introduction.html

## 支持方法
目前,该redis客户端组件,已经支持除去脚本外的所有方法(目前支持了178个方法):

- 连接方法(connection)
- 集群方法(cluster)
- geohash
- 哈希(hash)
- 键(keys)
- 列表(lists)
- 发布/订阅(pub/sub)
- 服务器(server)
- 字符串(string)
- 有序集合(sorted sets)
- 集合 (sets)
- 流（stream）
- 事务 (transaction)
- 管道支持 (pipe)

> 由于redis的命令较多,可能漏掉1,2个命令


## redis使用示例
```php
<?php
include "../vendor/autoload.php";
go(function (){
    $redis = new \EasySwoole\Redis\Redis(new \EasySwoole\Redis\Config\RedisConfig([
        'host' => '127.0.0.1',
        'port' => '6379',
        'auth' => 'easyswoole',
        'serialize' => \EasySwoole\Redis\Config\RedisConfig::SERIALIZE_NONE
    ]));
    var_dump($redis->set('a',1));
    var_dump($redis->get('a'));
});
```

## redis集群使用示例
```php
<?php
include "../vendor/autoload.php";
go(function () {
    $redis = new \EasySwoole\Redis\RedisCluster(new \EasySwoole\Redis\Config\RedisClusterConfig([
        ['172.16.253.156', 9001],
        ['172.16.253.156', 9002],
        ['172.16.253.156', 9003],
        ['172.16.253.156', 9004],
    ], [
        'auth' => '',
        'serialize' => \EasySwoole\Redis\Config\RedisConfig::SERIALIZE_PHP
    ]));
    var_dump($redis->set('a',1));
    var_dump($redis->get('a'));
    var_dump($redis->clusterKeySlot('a'));

});
```