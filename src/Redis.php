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
        $command = [$key, $val];
        if ($expireTime != null && $expireTime > 0) {
            $command[] = 'EX ' . $expireTime;
        }

        $data = [Command::SET, $key, $val];
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
        $data = [Command::GET, $key];
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
        $data = [Command::GETSET, $key, $value];
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
        return $recv->getData();
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

    public function mSetNx($data)
    {
        $command = [Command::MSETNX];
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
        return $recv->getData();
    }

    public function pSetEx($key, $expireTime, $value)
    {
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
        return $recv->getData();
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
            $result[$data[$i * 2]] = $data[$i * 2 + 1];
        }
        return $result;
    }

    public function hSet($key, $field, $value)
    {
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
        return $recv->getData();
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
        return $recv->getData();
    }

    public function hMSet($key, $data): bool
    {
        $command = [Command::HMSET, $key];
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
            if ($this->errorType == 'NOAUTH') {
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