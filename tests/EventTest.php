<?php
/**
 * Created by PhpStorm.
 * User: Tioncico
 * Date: 2019/9/24 0024
 * Time: 16:16
 */

namespace Test;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Config\RedisClusterConfig;
use EasySwoole\Redis\Config\RedisConfig;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\RedisCluster;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    /**
     * @var $redis Redis
     */
    protected $redis;
    /**
     * @var $redis RedisCluster
     */
    protected $redisCluster;
    protected function setUp()
    {
        parent::setUp();
        $redisConfig = new RedisConfig([
            'host' => REDIS_HOST,
            'port' => REDIS_PORT,
            'auth' => REDIS_AUTH,
        ]);
        $this->redis = new Redis($redisConfig);

        $this->redisCluster = new RedisCluster(new RedisClusterConfig(REDIS_CLUSTER_SERVER_LIST, [
            'auth' => REDIS_CLUSTER_AUTH,
        ]));

    }

    function testRedis(){
        $redis = $this->redis;
        $redis->getConfig()->onBeforeEvent(function ($commandName,$commandData){
            $this->assertEquals(CommandConst::SET,$commandName);
            $this->assertEquals('a',$commandData[0]);
            $this->assertEquals('1',$commandData[1]);
        });
        $redis->getConfig()->onAfterEvent(function ($commandName,$commandData,$result){
            $this->assertEquals(CommandConst::SET,$commandName);
            $this->assertEquals('a',$commandData[0]);
            $this->assertEquals('1',$commandData[1]);
            $this->assertEquals(true,$result);
        });
        $redis->set('a',1);
    }

    function testCluster(){
        $redis = $this->redisCluster;
        $redis->getConfig()->onBeforeEvent(function ($commandName,$commandData){
            $this->assertEquals(CommandConst::SET,$commandName);
            $this->assertEquals('a',$commandData[0]);
            $this->assertEquals('1',$commandData[1]);
        });
        $redis->getConfig()->onAfterEvent(function ($commandName,$commandData,$result){
            $this->assertEquals(CommandConst::SET,$commandName);
            $this->assertEquals('a',$commandData[0]);
            $this->assertEquals('1',$commandData[1]);
            $this->assertEquals(true,$result);
        });
        $redis->set('a',1);
    }

    function testPIPE(){

        $redis = $this->redis;
        $redis->getConfig()->onBeforeEvent(function ($commandName,$commandData){
            var_dump ($commandName,$commandData);
            $this->assertEquals(1,1);
        });
        $redis->getConfig()->onAfterEvent(function ($commandName,$commandData,$result){
            var_dump ($commandName,$commandData,$result);
            $this->assertEquals(1,1);
        });
        $redis->startPipe();
        $redis->set('a',1);
        $redis->get('a',1);
        $redis->execPipe();
    }

}
