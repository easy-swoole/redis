<?php
/**
 * Created by PhpStorm.
 * User: Tioncico
 * Date: 2019/9/26 0026
 * Time: 14:45
 */

namespace EasySwoole\Redis\Exception;

class RedisException extends \Exception
{
    protected $redisErrorCode;
    protected $redisErrorMsg;

    public function __construct($redisErrorMsg, $redisErrorCode=0)
    {
        $this->message = $redisErrorMsg;
        $this->redisErrorMsg = $redisErrorMsg;
        $this->redisErrorCode = $redisErrorCode;
    }

    /**
     * @return int
     */
    public function getRedisErrorCode()
    {
        return $this->redisErrorCode;
    }

    /**
     * @return mixed
     */
    public function getRedisErrorMsg()
    {
        return $this->redisErrorMsg;
    }
}
