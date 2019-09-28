<?php


namespace EasySwoole\Redis;


use EasySwoole\Redis\Config\RedisConfig;
use EasySwoole\Redis\Exception\RedisException;

class Redis
{
    protected $config;
    /** @var Client */
    protected $client;
    protected $isConnected;
    protected $tryConnectTimes = 0;
    protected $lastSocketError;
    protected $lastSocketErrno;
    protected $errorType;
    protected $errorMsg;

    public function __construct(RedisConfig $config)
    {
        $this->config = $config;
    }

    ######################服务器连接方法######################

    public function connect(float $timeout = null): bool
    {
        if ($this->isConnected) {
            return true;
        }
        if ($timeout === null) {
            $timeout = $this->config->getTimeout();
        }
        if ($this->client == null) {
            $this->client = new Client($this->config->getHost(), $this->config->getPort());
        }
        $this->isConnected = $this->client->connect($timeout);
        return $this->isConnected;
    }

    protected function reset()
    {
        $this->tryConnectTimes = 0;
        $this->lastSocketErrno = 0;
        $this->lastSocketError = '';
        $this->errorMsg = '';
        $this->errorType = '';
    }

    public function auth($password): bool
    {
        $data = ['auth', $password];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return true;
    }

    public function echo($str)
    {
        $data = ['echo', $str];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function ping()
    {
        $data = ['ping'];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function select($db): bool
    {
        $data = ['select', $db];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return true;
    }

    ######################服务器连接方法######################

    ######################key操作方法######################

    public function del($key)
    {
        $data = ['del', $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function dump($key)
    {
        $data = ['dump', $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function exists($key)
    {
        $data = ['exists', $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function expire($key, $expireTime = 60)
    {
        $data = ['expire', $key, $expireTime];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function expireAt($key, $expireTime)
    {
        $data = ['expireat', $key, $expireTime];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function keys($pattern)
    {
        $data = ['keys', $pattern];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function move($key, $db)
    {
        $data = ['move', $key, $db];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function persist($key)
    {
        $data = ['persist', $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function pTTL($key)
    {
        $data = ['pttl', $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function ttl($key)
    {
        $data = ['ttl', $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function randomKey()
    {
        $data = ['randomkey'];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function rename($key, $new_key): bool
    {
        $data = ['rename', $key, $new_key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return true;
    }

    public function renameNx($key, $new_key)
    {
        $data = ['renamenx', $key, $new_key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function type($key)
    {
        $data = ['type', $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    ######################key操作方法######################


    ######################字符串方法######################

    public function set($key, $val, $expireTime = null): bool
    {
        $command = [$key, $val];
        if ($expireTime != null && $expireTime > 0) {
            $command[] = 'EX ' . $expireTime;
        }

        $data = ['set', $key, $val];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return true;
    }

    public function get($key)
    {
        $data = ['get', $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function getRange($key, $start, $end)
    {
        $data = ['getrange', $key, $start, $end];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function getSet($key, $value)
    {
        $data = ['getSet', $key, $value];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function getBit($key, $offset)
    {
        $data = ['getBit', $key, $offset];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function mGet(...$keys)
    {
        $data = ['mget', $keys];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function setBit($key, $offset, $value)
    {
        $data = ['setbit', $key, $offset, $value];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function setEx($key, $expireTime, $value)
    {
        $data = ['setex', $key, $expireTime, $value];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function setNx($key, $value)
    {
        $data = ['setnx', $key, $value];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function setRange($key, $offset, $value)
    {
        $data = ['setrange', $key, $offset, $value];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function strLen($key)
    {
        $data = ['strlen', $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function incr($key)
    {
        $data = ['incr', $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function incrBy($key, $value)
    {
        $data = ['incrby', $key, $value];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function decr($key)
    {
        $data = ['decr', $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function decrBy($key, $value)
    {
        $data = ['decrby', $key, $value];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    ######################字符串方法######################


    ######################hash操作方法####################

    public function hSet($key, $field, $value)
    {
        $data = ['hset', $key, $field, $value];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function hGet($key, $field)
    {
        $data = ['hget', $key, $field];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function hDel($key, ...$field)
    {
        $command = array_merge(['hdel', $key], $field);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function hExists($key, $field)
    {
        $data = ['hexists', $key, $field];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function hValS($key)
    {
        $data = ['hvals', $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function hGetAll($key)
    {
        $data = ['hgetall', $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        $result = [];
        $data = $recv->getData();
        $dataCount = count($data);
        for ($i = 0; $i < $dataCount / 2; $i++) {
            $result[$data[$i * 2]] = $data[$i * 2 + 1];
        }
        return $result;
    }

    public function hKeys($key)
    {
        $data = ['hkeys', $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function hLen($key)
    {
        $data = ['hlen', $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function hMGet($key, ...$field)
    {
        $command = array_merge(['hmget', $key], $field);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function hMSet($key, $data): bool
    {
        $command = ['hmset', $key];
        foreach ($data as $key => $value) {
            $command[] = $key;
            $command[] = $value;
        }
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return true;
    }
    ######################hash操作方法######################

    ###################### 发送接收tcp流数据 ######################
    protected function sendCommand(array $com): bool
    {
        while ($this->tryConnectTimes <= $this->config->getReconnectTimes()) {
            if ($this->connect()) {
                if ($this->client->sendCommand($com)) {
                    $this->reset();
                    return true;
                } else {
                    /*
                     * 发送失败的时候，强制切断链接进入重连发送逻辑
                     */
                    $this->client->close();;
                }
            }
            $this->lastSocketError = $this->client->socketError();
            $this->lastSocketErrno = $this->client->socketErrno();
            $this->tryConnectTimes++;
        }
        return false;
    }

    protected function recv(): ?Response
    {
        $recv = $this->client->recv($this->config->getTimeout());
        if ($recv->getStatus() == $recv::STATUS_ERR) {
            $this->errorType = $recv->getErrorType();
            $this->errorMsg = $recv->getMsg();
            //未登录
            if ($this->errorType=='NOAUTH'){
                throw new RedisException($recv->getMsg());
            }
            return null;
        } elseif ($recv->getStatus() == $recv::STATUS_OK) {
            return $recv;
        } elseif ($recv->getStatus() == $recv::STATUS_TIMEOUT) {
            /*
             * 接收失败的时候，强制切断链接进入重连发送逻辑
             */
            $this->client->close();;
            $this->lastSocketError = $this->client->socketError();
            $this->lastSocketErrno = $this->client->socketErrno();
            return null;
        }
    }
    ###################### 发送接收tcp流数据 ######################

    /**
     * @return mixed
     */
    public function getLastSocketError()
    {
        return $this->lastSocketError;
    }

    /**
     * @return mixed
     */
    public function getLastSocketErrno()
    {
        return $this->lastSocketErrno;
    }

    /**
     * @return mixed
     */
    public function getErrorType()
    {
        return $this->errorType;
    }

    /**
     * @return mixed
     */
    public function getErrorMsg()
    {
        return $this->errorMsg;
    }

    protected function serialize($val)
    {
        switch ($this->config->getSerialize()) {
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

    protected function unserialize($val)
    {
        switch ($this->config->getSerialize()) {
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