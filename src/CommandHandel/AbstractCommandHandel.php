<?php
/**
 * Created by PhpStorm.
 * User: tioncico
 * Date: 19-10-3
 * Time: 下午6:19
 */

namespace EasySwoole\Redis\CommandHandel;


use EasySwoole\Redis\Config\RedisConfig;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

Abstract class AbstractCommandHandel
{
    protected  $commandName;
    protected $redis;
    function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    abstract function getCommand(...$data);

    abstract function getData(Response $recv);

    /**
     * @return mixed
     */
    public  function getCommandName()
    {
        return $this->commandName;
    }
    protected  function serialize($val)
    {
        switch ($this->redis->getConfig()->getSerialize()) {
            case RedisConfig::SERIALIZE_PHP:
                {
                    return serialize($val);
                    break;
                }

            case RedisConfig::SERIALIZE_JSON:
                {
                    return json_encode($val, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
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

}