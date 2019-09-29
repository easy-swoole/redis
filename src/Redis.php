<?php


namespace EasySwoole\Redis;


use EasySwoole\Redis\Config\RedisConfig;
use EasySwoole\Redis\Exception\RedisException;
use EasySwoole\Redis\CommandConst as Command;

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

    /**
     * @var bool 是否停止消息订阅
     */
    protected $subscribeStop = true;


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
        if ($this->isConnected && !empty($this->config->getAuth())) {
            if (!$this->auth($this->config->getAuth())) {
                $this->isConnected = false;
                throw new RedisException("auth to redis host {$this->config->getHost()}:{$this->config->getPort()} fail");
            }
        }
        return $this->isConnected;
    }

    protected function reset()
    {
        $this->tryConnectTimes = 0;
        $this->lastSocketErrno = 0;
        $this->lastSocketError = '';
        $this->subscribeStop = true;
        $this->errorMsg = '';
        $this->errorType = '';
    }

    public function auth($password): bool
    {
        $data = [Command::AUTH, $password];
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
        $data = [Command::ECHO, $str];
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
        $data = [Command::PING];
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
        $data = [Command::SELECT, $db];
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
        $data = [Command::DEL, $key];
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
        $data = [Command::DUMP, $key];
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
        $data = [Command::EXISTS, $key];
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
        $data = [Command::EXPIRE, $key, $expireTime];
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
        $data = [Command::EXPIREAT, $key, $expireTime];
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
        $data = [Command::KEYS, $pattern];
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
        $data = [Command::MOVE, $key, $db];
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
        $data = [Command::PERSIST, $key];
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
        $data = [Command::PTTL, $key];
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
        $data = [Command::TTL, $key];
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
        $data = [Command::RANDOMKEY];
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
        $data = [Command::RENAME, $key, $new_key];
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
        $data = [Command::RENAMENX, $key, $new_key];
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
        $data = [Command::TYPE, $key];
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
        $val = $this->serialize($val);
        $command = [Command::SET, $key, $val];
        if ($expireTime != null && $expireTime > 0) {
            $command[] = 'EX ' . $expireTime;
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

    public function get($key)
    {
        $data = [Command::GET, $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        $data = $recv->getData();
        return $this->unserialize($data);
    }

    public function getRange($key, $start, $end)
    {
        $data = [Command::GETRANGE, $key, $start, $end];
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
        $value = $this->serialize($value);
        $data = [Command::GETSET, $key, $value];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        $data = $recv->getData();
        return $this->unserialize($data);
    }

    public function getBit($key, $offset)
    {
        $data = [Command::GETBIT, $key, $offset];
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
        $command = array_merge([Command::MGET], $keys);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        $data = $recv->getData();
        foreach ($data as $key => $value) {
            $data[$key] = $this->unSerialize($value);
        }

        return $data;
    }

    public function setBit($key, $offset, $value)
    {
        $data = [Command::SETBIT, $key, $offset, $value];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function setEx($key, $expireTime, $value): bool
    {
        $value = $this->serialize($value);
        $data = [Command::SETEX, $key, $expireTime, $value];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return true;
    }

    public function setNx($key, $value)
    {
        $data = [Command::SETNX, $key, $value];
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
        $data = [Command::SETRANGE, $key, $offset, $value];
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
        $data = [Command::STRLEN, $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function mSet($data): bool
    {
        $command = [Command::MSET];
        foreach ($data as $key => $value) {
            $command[] = $key;
            $command[] = $this->serialize($value);
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

    public function mSetNx($data)
    {
        $command = [Command::MSETNX];
        foreach ($data as $key => $value) {
            $command[] = $key;
            $command[] = $this->serialize($value);
        }
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function pSetEx($key, $expireTime, $value)
    {
        $value = $this->serialize($value);
        $data = [Command::PSETEX, $key, $expireTime, $value];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return true;
    }

    public function incr($key)
    {
        $data = [Command::INCR, $key];
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
        $data = [Command::INCRBY, $key, $value];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function incrByFloat($key, $value)
    {
        $data = [Command::INCRBYFLOAT, $key, $value];
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
        $data = [Command::DECR, $key];
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
        $data = [Command::DECRBY, $key, $value];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function appEnd($key, $value)
    {
        $data = [Command::APPEND, $key, $value];
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

    public function hDel($key, ...$field)
    {
        $command = array_merge([Command::HDEL, $key], $field);
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
        $data = [Command::HEXISTS, $key, $field];
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
        $data = [Command::HGET, $key, $field];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }

        $data = $recv->getData();
        return $this->unserialize($data);
    }

    public function hGetAll($key)
    {
        $data = [Command::HGETALL, $key];
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
            $result[$data[$i * 2]] = $this->unserialize($data[$i * 2 + 1]);
        }
        return $result;
    }

    public function hSet($key, $field, $value)
    {
        $value = $this->serialize($value);
        $data = [Command::HSET, $key, $field, $value];
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
        $data = [Command::HVALS, $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        $data = $recv->getData();
        foreach ($data as $key => $value) {
            $data[$key] = $this->unSerialize($value);
        }
        return $data;
    }

    public function hKeys($key)
    {
        $data = [Command::HKEYS, $key];
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
        $data = [Command::HLEN, $key];
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
        $command = array_merge([Command::HMGET, $key], $field);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        $data = $recv->getData();
        foreach ($data as $key => $value) {
            $data[$key] = $this->unSerialize($value);
        }

        return $data;
    }

    public function hMSet($key, $data): bool
    {
        $command = [Command::HMSET, $key];
        foreach ($data as $key => $value) {
            $command[] = $key;
            $command[] = $this->serialize($value);
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

    public function hIncrBy($key, $field, $increment)
    {
        $data = [Command::HINCRBY, $key, $field, $increment];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function hIncrByFloat($key, $field, $increment)
    {
        $data = [Command::HINCRBYFLOAT, $key, $field, $increment];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function hSetNx($key, $field, $value)
    {
        $value = $this->serialize($value);
        $data = [Command::HSETNX, $key, $field, $value];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

//    public function hScan($key, $iterator, $pattern = '', $count = 0)
//    {
//        $data = [Command::HSCAN, $key, $iterator, $pattern, $count];
//        if (!$this->sendCommand($data)) {
//            return false;
//        }
//        $recv = $this->recv();
//        if ($recv === null) {
//            return false;
//        }
//        return $recv->getData();
//    }

    ######################hash操作方法######################

    ######################列表操作方法######################

    public function bLPop($key1, ...$data)
    {
        $command = array_merge([Command::BLPOP, $key1], $data);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return [$recv->getData()[0] => $this->unSerialize($recv->getData()[1])];
    }

    public function bRPop($key1, ...$data)
    {
        $command = array_merge([Command::BLPOP, $key1], $data);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return [$recv->getData()[0] => $this->unSerialize($recv->getData()[1])];
    }

    public function bRPopLPush($source, $destination, $timeout)
    {
        $data = [Command::BRPOPLPUSH, $source, $destination, $timeout];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        $data = $recv->getData();
        return $this->unserialize($data);
    }

    public function lIndex($key, $index)
    {
        $data = [Command::LINDEX, $key, $index];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        $data = $recv->getData();
        return $this->unSerialize($data);
    }

    public function lInsert($key, bool $isBefore, $pivot, $value)
    {
        $value = $this->serialize($value);
        $pivot = $this->serialize($pivot);
        $data = [Command::LINSERT, $key, $isBefore == true ? 'BEFORE' : 'AFTER', $pivot, $value];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function lLen($key)
    {
        $data = [Command::LLEN, $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function lPop($key)
    {
        $data = [Command::LPOP, $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        $data = $recv->getData();
        return $this->unserialize($data);
    }

    public function lPush($key, ...$data)
    {
        foreach ($data as $k => $va) {
            $data[$k] = $this->serialize($va);
        }
        $command = array_merge([Command::LPUSH, $key], $data);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function lPuShx($key, $value)
    {
        $value = $this->serialize($value);
        $data = [Command::LPUSHX, $key, $value];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function lRange($key, $start, $stop)
    {
        $data = [Command::LRANGE, $key, $start, $stop];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        $data = $recv->getData();
        foreach ($data as $key => $va) {
            $data[$key] = $this->unSerialize($va);
        }

        return $data;
    }

    public function lRem($key, $count, $value)
    {
        $value = $this->serialize($value);
        $data = [Command::LREM, $key, $count, $value];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function lSet($key, $index, $value): bool
    {
        $value = $this->serialize($value);
        $data = [Command::LSET, $key, $index, $value];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return true;
    }

    public function lTrim($key, $start, $stop): bool
    {
        $data = [Command::LTRIM, $key, $start, $stop];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return true;
    }

    public function rPop($key)
    {
        $data = [Command::RPOP, $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        $data = $recv->getData();
        return $this->unserialize($data);
    }

    public function rPopLPush($source, $destination)
    {
        $data = [Command::RPOPLPUSH, $source, $destination];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        $data = $recv->getData();
        return $this->unserialize($data);
    }

    public function rPush($key, ...$data)
    {
        foreach ($data as $k => $va) {
            $data[$k] = $this->serialize($va);
        }
        $command = array_merge([Command::LPUSH, $key], $data);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function rPuShx($key, $value)
    {
        $value = $this->serialize($value);
        $data = [Command::RPUSHX, $key, $value];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }
    ######################列表操作方法######################

    ######################集合操作方法######################
    public function sAdd($key, ...$data)
    {
        foreach ($data as $k => $va) {
            $data[$k] = $this->serialize($va);
        }
        $command = array_merge([Command::SADD, $key], $data);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function sCard($key)
    {
        $data = [Command::SCARD, $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function sDiff($key1, ...$keys)
    {
        $command = array_merge([Command::SDIFF, $key1], $keys);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        $data = $recv->getData();
        foreach ($data as $key => $value) {
            $data[$key] = $this->unSerialize($value);
        }
        return $data;
    }

    public function sDiffStore($destination, ...$keys)
    {
        $command = array_merge([Command::SDIFFSTORE, $destination], $keys);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function sInter($key1, ...$keys)
    {
        $command = array_merge([Command::SINTER, $key1], $keys);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        $data = $recv->getData();
        foreach ($data as $key => $value) {
            $data[$key] = $this->unSerialize($value);
        }
        return $data;
    }

    public function sInterStore($destination, ...$keys)
    {
        $command = array_merge([Command::SINTERSTORE, $destination], $keys);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function sIsMember($key, $member)
    {
        $member = $this->serialize($member);
        $data = [Command::SISMEMBER, $key, $member];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function sMembers($key)
    {
        $data = [Command::SMEMBERS, $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        $data = $recv->getData();
        foreach ($data as $key => $value) {
            $data[$key] = $this->unSerialize($value);
        }
        return $data;
    }

    public function sMove($source, $destination, $member)
    {
        $member = $this->serialize($member);
        $data = [Command::SMOVE, $source, $destination, $member];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function sPop($key)
    {
        $data = [Command::SPOP, $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        $data = $recv->getData();
        $data = $this->unSerialize($data);
        return $data;
    }

    public function sRandMemBer($key, $count = null)
    {
        $data = [Command::SRANDMEMBER, $key];
        if ($count !== null) {
            $data[] = $count;
        }
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        $data = $recv->getData();
        foreach ($data as $key => $value) {
            $data[$key] = $this->unSerialize($value);
        }
        return $data;
    }

    public function sRem($key, $member1, ...$members)
    {
        $member1 = $this->serialize($member1);
        foreach ($members as $k => $va) {
            $members[$k] = $this->serialize($va);
        }
        $command = array_merge([Command::SREM, $key, $member1], $members);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function sUnion($key1, ...$keys)
    {
        $command = array_merge([Command::SUNION, $key1], $keys);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        $data = $recv->getData();
        foreach ($data as $key => $value) {
            $data[$key] = $this->unSerialize($value);
        }
        return $data;
    }

    public function sUnIomStore($destination, $key1, ...$keys)
    {
        $command = array_merge([Command::SUNIONSTORE, $destination, $key1], $keys);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    /*    public function sScan($key,$cursor,$pattern,$count)
        {
            $data = [Command::SSCAN,$key,$cursor,$pattern,$count];
            if (!$this->sendCommand($data)) {
                return false;
            }
            $recv = $this->recv();
            if ($recv === null) {
                return false;
            }
            return $recv->getData();
        }*/

    ######################集合操作方法######################

    ######################有序集合操作方法######################
    public function zAdd($key, $score1, $member1, ...$data)
    {
        $member1 = $this->serialize($member1);
        foreach ($data as $k => $va) {
            if ($k % 2 != 0) {
                $data[$k] = $this->serialize($va);
            }
        }

        $command = array_merge([Command::ZADD, $key, $score1, $member1], $data);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function zCard($key)
    {
        $data = [Command::ZCARD, $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function zCount($key, $min, $max)
    {
        $data = [Command::ZCOUNT, $key, $min, $max];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function zInCrBy($key, $increment, $member)
    {
        $data = [Command::ZINCRBY, $key, $increment, $member];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function zInTerStore($destination, $numKeys, $key, ...$data)
    {
        $command = array_merge([Command::ZINTERSTORE, $destination, $numKeys, $key,], $data);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function zLexCount($key, $min, $max)
    {
        $data = [Command::ZLEXCOUNT, $key, $min, $max];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function zRange($key, $start, $stop, $withScores = false)
    {
        $data = [Command::ZRANGE, $key, $start, $stop];
        if ($withScores == true) {
            $data[] = 'WITHSCORES';
        }
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        $data = $recv->getData();
        if ($withScores == true) {
            $result = [];
            foreach ($data as $k => $va) {
                if ($k % 2 == 0) {
                    $result[$this->unSerialize($va)] = 0;
                } else {
                    $result[$this->unSerialize($data[$k - 1])] = $va;
                }
            }
        } else {
            $result = [];
            foreach ($data as $k => $va) {
                $result[$k] = $this->unSerialize($va);
            }
        }

        return $result;
    }

    public function zRangeByLex($key, $min, $max, ...$data)
    {
        $command = array_merge([Command::ZRANGEBYLEX, $key, $min, $max], $data);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        $data = $recv->getData();
        foreach ($data as $key => $va) {
            $data[$key] = $this->unSerialize($va);
        }

        return $data;
    }

    public function zRangeByScore($key, $min, $max, $withScores = false, ...$data)
    {
        if ($withScores == true) {
            $command = array_merge([Command::ZRANGEBYSCORE, $key, $min, $max, 'WITHSCORES'], $data);
        } else {
            $command = array_merge([Command::ZRANGEBYSCORE, $key, $min, $max], $data);
        }

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }

        $data = $recv->getData();
        if ($withScores == true) {
            $result = [];
            foreach ($data as $k => $va) {
                if ($k % 2 == 0) {
                    $result[$this->unSerialize($va)] = 0;
                } else {
                    $result[$this->unSerialize($data[$k - 1])] = $va;
                }
            }
        } else {
            $result = [];
            foreach ($data as $k => $va) {
                $result[$k] = $this->unSerialize($va);
            }
        }
        return $result;
    }

    public function zRank($key, $member)
    {
        $member = $this->serialize($member);
        $data = [Command::ZRANK, $key, $member];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function zRem($key, $member, ...$members)
    {
        $member = $this->serialize($member);
        foreach ($members as $k => $va) {
            $members[$k] = $this->serialize($va);
        }
        $command = array_merge([Command::ZREM, $key, $member], $members);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function zRemRangeByLex($key, $min, $max)
    {
        $data = [Command::ZREMRANGEBYLEX, $key, $min, $max];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function zRemRangeByRank($key, $start, $stop)
    {
        $data = [Command::ZREMRANGEBYRANK, $key, $start, $stop];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function zRemRangeByScore($key, $min, $max)
    {
        $data = [Command::ZREMRANGEBYSCORE, $key, $min, $max];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function zRevRange($key, $start, $stop, $withScores = false)
    {
        $data = [Command::ZREVRANGE, $key, $start, $stop];
        if ($withScores == true) {
            $data[] = 'WITHSCORES';
        }
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        $data = $recv->getData();
        if ($withScores == true) {
            $result = [];
            foreach ($data as $k => $va) {
                if ($k % 2 == 0) {
                    $result[$this->unSerialize($va)] = 0;
                } else {
                    $result[$this->unSerialize($data[$k - 1])] = $va;
                }
            }
        } else {
            $result = [];
            foreach ($data as $k => $va) {
                $result[$k] = $this->unSerialize($va);
            }
        }
        return $result;
    }

    public function zRevRangeByScore($key, $max, $min, $withScores = false)
    {
        $data = [Command::ZREVRANGEBYSCORE, $key, $max, $min];
        if ($withScores == true) {
            $data[] = 'WITHSCORES';
        }
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        $data = $recv->getData();
        if ($withScores == true) {
            $result = [];
            foreach ($data as $k => $va) {
                if ($k % 2 == 0) {
                    $result[$this->unSerialize($va)] = 0;
                } else {
                    $result[$this->unSerialize($data[$k - 1])] = $va;
                }
            }
        } else {
            $result = [];
            foreach ($data as $k => $va) {
                $result[$k] = $this->unSerialize($va);
            }
        }
        return $result;
    }

    public function zRevRank($key, $member)
    {
        $member = $this->serialize($member);
        $data = [Command::ZREVRANK, $key, $member];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function zScore($key, $member)
    {
        $member = $this->serialize($member);
        $data = [Command::ZSCORE, $key, $member];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function zUnionStore($destination, $keyNum, $key, ...$data)
    {
        $command = array_merge([Command::ZUNIONSTORE, $destination, $keyNum, $key], $data);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    /*public function zScan($key, $cursor, $pattern, $count)
    {
        $data = [Command::ZSCAN, $key, $cursor, $pattern, $count];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }*/
    ######################有序集合操作方法######################

    ######################HyperLogLog操作方法######################

    public function pfAdd($key, ...$data)
    {
        $command = array_merge([Command::PFADD, $key], $data);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function pfCount($key, ...$keys)
    {
        $command = array_merge([Command::PFCOUNT, $key], $keys);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function pfMerge($deStKey, $sourceKey, ...$sourceKeys)
    {
        $command = array_merge([Command::PFMERGE, $deStKey, $sourceKey], $sourceKeys);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return true;
    }

    ######################HyperLogLog操作方法######################

    ######################发布订阅操作方法(待测试)######################

    public function pSubscribe($callback, $pattern, ...$patterns)
    {
        $command = array_merge([Command::PSUBSCRIBE, $pattern], $patterns);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv(-1);
        if ($recv === null) {
            return false;
        }
        $this->subscribeStop = false;
        while ($this->subscribeStop == false) {
            $recv = $this->recv(-1);
            if ($recv === null) {
                return false;
            }
            if ($recv->getData()[0] == 'pmessage') {
                call_user_func($callback, $this, $recv->getData()[2], $recv->getData()[3]);
            }
        }
    }

    public function pubSub($subCommand, ...$arguments)
    {
        $command = array_merge([Command::PUBSUB, $subCommand,], $arguments);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function publish($channel, $message)
    {
        $data = [Command::PUBLISH, $channel, $message];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function pUnSubscribe($pattern, ...$patterns)
    {
        $command = array_merge([Command::PUNSUBSCRIBE, $pattern], $patterns);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function subscribe($callback, $channel, ...$channels)
    {
        $command = array_merge([Command::SUBSCRIBE, $channel], $channels);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv(-1);
        if ($recv === null) {
            return false;
        }
        $this->subscribeStop = false;
        while ($this->subscribeStop == false) {
            $recv = $this->recv(-1);
            if ($recv === null) {
                return false;
            }
            if ($recv->getData()[0] == 'message') {
                call_user_func($callback, $this, $recv->getData()[1], $recv->getData()[2]);
            }
        }
    }

    public function unsubscribe($channel, ...$channels)
    {
        $command = array_merge([Command::UNSUBSCRIBE, $channel], $channels);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function subscribeStop(): void
    {
        $this->subscribeStop = true;
    }


    ######################发布订阅操作方法(待测试)######################

    ######################事务操作方法(待测试)######################

    /* public function discard():bool
     {
         $data = [Command::DISCARD];
         if (!$this->sendCommand($data)) {
             return false;
         }
         $recv = $this->recv();
         if ($recv === null) {
             return false;
         }
         return true;
     }

     public function exec()
     {
         $data = [Command::EXEC];
         if (!$this->sendCommand($data)) {
             return false;
         }
         $recv = $this->recv();
         if ($recv === null) {
             return false;
         }
         return $recv->getData();
     }

     public function multi():bool
     {
         $data = [Command::MULTI];
         if (!$this->sendCommand($data)) {
             return false;
         }
         $recv = $this->recv();
         if ($recv === null) {
             return false;
         }
         return true;
     }

     public function unWatch():bool
     {
         $data = [Command::UNWATCH];
         if (!$this->sendCommand($data)) {
             return false;
         }
         $recv = $this->recv();
         if ($recv === null) {
             return false;
         }
         return true;
     }

     public function watch($key,...$keys):bool
     {
         $command = array_merge([Command::WATCH, $key], $keys);
         if (!$this->sendCommand($command)) {
             return false;
         }
         $recv = $this->recv();
         if ($recv === null) {
             return false;
         }
         return true;
     }*/
    ######################事务操作方法(待测试)######################


    ######################脚本操作方法(待测试)######################

    /*public function eval($script, $keyNum, $key,...$data)
    {
        $command = array_merge([Command::EVAL,$script, $keyNum, $key,], $data);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function evalSha($sha1,$keyNum,$key,...$data)
    {
        $command = array_merge([Command::EVAL,$sha1, $keyNum, $key,], $data);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function scriptExists($script,...$scripts)
    {
        $command = array_merge([Command::SCRIPT_EXISTS,$script], $scripts);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function scriptFlush()
    {
        $data = [Command::SCRIPT_FLUSH];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function scriptKill():bool
    {
        $data = [Command::SCRIPT_KILL];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return true;
    }

    public function scriptLoad($script)
    {
        $data = [Command::SCRIPT_LOAD,$script];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }*/
    ######################脚本操作方法(待测试)######################


    ######################脚本操作方法(待测试)######################



######################服务器操作方法######################

    public function bgReWriteAof()
    {
        $data = [Command::BGREWRITEAOF];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function bgSave()
    {
        $data = [Command::BGSAVE];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function clientKill($ip,$id)
    {
        $data = [Command::CLIENT,$ip,$id];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function clientList()
    {
        $data = [Command::CLIENT];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function clientGetName()
    {
        $data = [Command::CLIENT];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function clientPause($PAUSE,$timeout)
    {
        $data = [Command::CLIENT,$PAUSE,$timeout];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function clientSetName($connectionName)
    {
        $data = [Command::CLIENT, $connectionName];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function clusterSlots()
    {
        $data = [Command::CLUSTER];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function command()
    {
        $data = [Command::COMMAND];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function commandCount()
    {
        $data = [Command::COMMAND];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function commandGetKeys()
    {
        $data = [Command::COMMAND];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function time()
    {
        $data = [Command::TIME];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function commandInfo($commandName,$commandName1)
    {
        $data = [Command::COMMAND,$commandName,$commandName1];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function configGet($parameter)
    {
        $data = [Command::CONFIG,$parameter];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function configRewrite()
    {
        $data = [Command::CONFIG];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function configSet($parameter,$value)
    {
        $data = [Command::CONFIG,$parameter,$value];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function configResetsTat()
    {
        $data = [Command::CONFIG];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function dBSize()
    {
        $data = [Command::DBSIZE];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function debugObject($key)
    {
        $data = [Command::DEBUG, $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function debugSegfault()
    {
        $data = [Command::DEBUG ];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function flushAll()
    {
        $data = [Command::FLUSHALL];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function flushDb()
    {
        $data = [Command::FLUSHDB];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function info($section)
    {
        $data = [Command::INFO, $section];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function lastSave()
    {
        $data = [Command::LASTSAVE];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function monitor()
    {
        $data = [Command::MONITOR];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function role()
    {
        $data = [Command::ROLE];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function save()
    {
        $data = [Command::SAVE];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function shutdown($noSave,$save)
    {
        $data = [Command::SHUTDOWN,$noSave,$save];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function slaveOf($host,$port)
    {
        $data = [Command::SLAVEOF, $host,$port];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function slowLog($subcommand,$argument)
    {
        $data = [Command::SLOWLOG,$subcommand,$argument];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function SYNC()
    {
        $data = [Command::SYNC];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }
    ######################服务器操作方法######################


    ###################### 发送接收tcp流数据 ######################
    protected function sendCommand(array $com): bool
    {
        while ($this->tryConnectTimes <= $this->config->getReconnectTimes()) {
            if ($this->connect()) {
                if ($this->client->sendCommand($com)) {
                    $this->reset();
                    return true;
                }
            }
            $this->disconnect();
            $this->tryConnectTimes++;
        }
        /*
         * 链接超过重连次数，应该抛出异常
         */
        throw new RedisException("connect to redis host {$this->config->getHost()}:{$this->config->getPort()} fail after retry {$this->tryConnectTimes} times");
    }

    protected function recv($timeout = null): ?Response
    {
        $recv = $this->client->recv($timeout ?? $this->config->getTimeout());
        if ($recv->getStatus() == $recv::STATUS_ERR) {
            $this->errorType = $recv->getErrorType();
            $this->errorMsg = $recv->getMsg();
            //未登录
            if ($this->errorType == 'NOAUTH') {
                throw new RedisException($recv->getMsg());
            }
        } elseif ($recv->getStatus() == $recv::STATUS_OK) {
            return $recv;
        } elseif ($recv->getStatus() == $recv::STATUS_TIMEOUT) {
            $this->disconnect();
        }
        return null;
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

    function disconnect()
    {
        if ($this->isConnected) {
            $this->client->close();
            $this->isConnected = false;
            $this->lastSocketError = $this->client->socketError();
            $this->lastSocketErrno = $this->client->socketErrno();
        }
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

    protected function unSerialize($val)
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