<?php
/**
 * Created by PhpStorm.
 * User: tioncico
 * Date: 19-10-3
 * Time: 下午6:19
 */

namespace EasySwoole\Redis\CommandHandel;


use EasySwoole\Redis\Config\RedisConfig;
use EasySwoole\Redis\CrcHash;
use EasySwoole\Redis\Pipe;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\RedisCluster;
use EasySwoole\Redis\RedisTransaction;
use EasySwoole\Redis\Response;

Abstract class AbstractCommandHandel
{
    //命令名称
    protected $commandName;
    //$redis实例
    protected $redis;
    //命令key值
    protected $key;
    //slot值
    protected $slot;

    function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    function getCommand(...$data)
    {
        $commandData = $this->handelCommandData(...$data);
        //开启了管道
        if ($this->redis->getPipe() && $this->redis->getPipe()->isStartPipe() == true) {
            $this->redis->getPipe()->addCommand([$this->commandName, $commandData]);
            //事务命令忽略
            if (!in_array(strtolower($this->commandName), Pipe::IGNORE_COMMAND)) {
                return ['PIPE'];
            }
        }
        return $commandData;
    }

    function getData(Response $recv)
    {
        //开启了事务
        if ($this->redis->getTransaction() && $this->redis->getTransaction()->isTransaction() == true) {
            $this->redis->getTransaction()->addCommand($this->commandName);
            //事务命令忽略
            if (!in_array(strtolower($this->commandName), RedisTransaction::IGNORE_COMMAND)) {
                return 'QUEUED';
            }
        }

        //开启了管道
        if ($this->redis->getPipe() && $this->redis->getPipe()->isStartPipe() == true) {
            //事务命令忽略
            if (!in_array(strtolower($this->commandName), Pipe::IGNORE_COMMAND)) {
                return 'PIPE';
            }
        }

        if ($recv->getStatus() != $recv::STATUS_OK) {
            $this->redis->setErrorType($recv->getErrorType());
            $this->redis->setErrorMsg($recv->getMsg());
            return false;
        }
        return $this->handelRecv($recv);
    }

    abstract function handelCommandData(...$data);

    abstract function handelRecv(Response $recv);

    /**
     * @return mixed
     */
    public function getCommandName()
    {
        return $this->commandName;
    }

    protected function serialize($val)
    {
        switch ($this->redis->getConfig()->getSerialize()) {
            case RedisConfig::SERIALIZE_PHP:
                return serialize($val);
                break;
            case RedisConfig::SERIALIZE_JSON:
                return json_encode($val, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                break;
            default:
            case RedisConfig::SERIALIZE_NONE:
                return $val;
                break;
        }
    }

    protected function unSerialize($val)
    {
        switch ($this->redis->getConfig()->getSerialize()) {
            case RedisConfig::SERIALIZE_PHP:
                {
                    return unserialize($val);
                    break;
                }

            case RedisConfig::SERIALIZE_JSON:
                {
                    return json_decode($val, true);
                    break;
                }
            default:
            case RedisConfig::SERIALIZE_NONE:
                {
                    return $val;
                    break;
                }
        }
    }

    protected function setClusterExecClientByKey($key)
    {
        if ($this->redis instanceof RedisCluster) {
            /**
             * @var $redis RedisCluster
             */
            $redis = $this->redis;
            $client = $redis->getClientBySlotId(CrcHash::getRedisSlot($key));
            $redis->setDefaultClient($client);
        }
    }

}