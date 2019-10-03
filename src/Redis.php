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

    /**
     * @var bool 是否停止命令监听
     */
    protected $monitorStop = true;


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
        $this->monitorStop = true;
        $this->errorMsg = '';
        $this->errorType = '';
    }

    public function auth($password): bool
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\Auth($this);
        $command = $handelClass->getCommand($password);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
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
        $handelClass = new \EasySwoole\Redis\CommandHandel\Ping($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function select($db): bool
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\Select($this);
        $command = $handelClass->getCommand($db);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    ######################服务器连接方法######################

    ######################key操作方法######################

    public function del($key)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\Del($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function dump($key)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\Dump($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function exists($key)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\Exists($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function expire($key, $expireTime = 60)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\Expire($this);
        $command = $handelClass->getCommand($key, $expireTime);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function expireAt($key, $expireTime)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\ExpireAt($this);
        $command = $handelClass->getCommand($key, $expireTime);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function keys($pattern)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\Keys($this);
        $command = $handelClass->getCommand($pattern);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function move($key, $db)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\Move($this);
        $command = $handelClass->getCommand($key, $db);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function persist($key)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\Persist($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function pTTL($key)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\PTTL($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function ttl($key)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\Ttl($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function randomKey()
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\RandomKey($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function rename($key, $new_key): bool
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\Rename($this);
        $command = $handelClass->getCommand($key, $new_key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function renameNx($key, $new_key)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\RenameNx($this);
        $command = $handelClass->getCommand($key, $new_key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function type($key)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\Type($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    ######################key操作方法######################


    ######################字符串方法######################

    public function set($key, $val, $expireTime = null): bool
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\Set($this);
        $command = $handelClass->getCommand($key, $val, $expireTime);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function get($key)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\Get($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function getRange($key, $start, $end)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\GetRange($this);
        $command = $handelClass->getCommand($key, $start, $end);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function getSet($key, $value)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\GetSet($this);
        $command = $handelClass->getCommand($key, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function getBit($key, $offset)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\GetBit($this);
        $command = $handelClass->getCommand($key, $offset);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function mGet(...$keys)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\MGet($this);
        $command = $handelClass->getCommand(...$keys);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function setBit($key, $offset, $value)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\SetBit($this);
        $command = $handelClass->getCommand($key, $offset, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function setEx($key, $expireTime, $value): bool
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\SetEx($this);
        $command = $handelClass->getCommand($key, $expireTime, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function setNx($key, $value)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\SetNx($this);
        $command = $handelClass->getCommand($key, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function setRange($key, $offset, $value)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\SetRange($this);
        $command = $handelClass->getCommand($key, $offset, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function strLen($key)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\StrLen($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function mSet($data): bool
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\MSet($this);

        $command = $handelClass->getCommand($data);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function mSetNx($data)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\MSetNx($this);
        $command = $handelClass->getCommand($data);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function pSetEx($key, $expireTime, $value)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\PSetEx($this);
        $command = $handelClass->getCommand($key, $expireTime, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function incr($key)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\Incr($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function incrBy($key, $value)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\IncrBy($this);
        $command = $handelClass->getCommand($key, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function incrByFloat($key, $value)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\IncrByFloat($this);
        $command = $handelClass->getCommand($key, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function decr($key)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\Decr($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function decrBy($key, $value)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\DecrBy($this);
        $command = $handelClass->getCommand($key, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function appEnd($key, $value)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\AppEnd($this);
        $command = $handelClass->getCommand($key, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    ######################字符串方法######################


    ######################hash操作方法####################

    public function hDel($key, ...$field)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\HDel($this);
        $command = $handelClass->getCommand($key, ...$field);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function hExists($key, $field)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\HExists($this);
        $command = $handelClass->getCommand($key, $field);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function hGet($key, $field)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\HGet($this);
        $command = $handelClass->getCommand($key, $field);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function hGetAll($key)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\HGetAll($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function hSet($key, $field, $value)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\HSet($this);
        $command = $handelClass->getCommand($key, $field, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function hValS($key)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\HValS($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function hKeys($key)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\HKeys($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function hLen($key)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\HLen($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function hMGet($key, ...$field)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\HMGet($this);
        $command = $handelClass->getCommand($key, ...$field);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function hMSet($key, $data): bool
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\HMSet($this);
        $command = $handelClass->getCommand($key, $data);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function hIncrBy($key, $field, $increment)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\HIncrBy($this);
        $command = $handelClass->getCommand($key, $field, $increment);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function hIncrByFloat($key, $field, $increment)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\HIncrByFloat($this);
        $command = $handelClass->getCommand($key, $field, $increment);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function hSetNx($key, $field, $value)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\HSetNx($this);
        $command = $handelClass->getCommand($key, $field, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
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
        $handelClass = new \EasySwoole\Redis\CommandHandel\BLPop($this);
        $command = $handelClass->getCommand($key1, ...$data);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function bRPop($key1, ...$data)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\BRPop($this);
        $command = $handelClass->getCommand($key1, ...$data);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function bRPopLPush($source, $destination, $timeout)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\BRPopLPush($this);
        $command = $handelClass->getCommand($source, $destination, $timeout);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function lIndex($key, $index)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\LIndex($this);
        $command = $handelClass->getCommand($key, $index);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function lInsert($key, bool $isBefore, $pivot, $value)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\LInsert($this);
        $command = $handelClass->getCommand($key, $isBefore == true ? 'BEFORE' : 'AFTER', $pivot, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function lLen($key)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\LLen($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function lPop($key)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\LPop($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function lPush($key, ...$data)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\LPush($this);
        $command = $handelClass->getCommand($key, ...$data);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function lPuShx($key, $value)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\LPuShx($this);
        $command = $handelClass->getCommand($key, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function lRange($key, $start, $stop)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\LRange($this);
        $command = $handelClass->getCommand($key, $start, $stop);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function lRem($key, $count, $value)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\LRem($this);
        $command = $handelClass->getCommand($key, $count, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function lSet($key, $index, $value): bool
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\LSet($this);
        $command = $handelClass->getCommand($key, $index, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function lTrim($key, $start, $stop): bool
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\LTrim($this);
        $command = $handelClass->getCommand($key, $start, $stop);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function rPop($key)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\RPop($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function rPopLPush($source, $destination)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\RPopLPush($this);
        $command = $handelClass->getCommand($source, $destination);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function rPush($key, ...$data)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\RPush($this);
        $command = $handelClass->getCommand($key, ...$data);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function rPuShx($key, $value)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\RPuShx($this);
        $command = $handelClass->getCommand($key, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }
    ######################列表操作方法######################

    ######################集合操作方法######################
    public function sAdd($key, ...$data)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\SAdd($this);
        $command = $handelClass->getCommand($key, $data);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function sCard($key)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\SCard($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function sDiff($key1, ...$keys)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\SDiff($this);
        $command = $handelClass->getCommand($key1, $keys);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function sDiffStore($destination, ...$keys)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\SDiffStore($this);
        $command = $handelClass->getCommand($destination, $keys);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function sInter($key1, ...$keys)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\SInter($this);
        $command = $handelClass->getCommand($key1, $keys);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function sInterStore($destination, ...$keys)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\SInterStore($this);
        $command = $handelClass->getCommand($destination, $keys);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function sIsMember($key, $member)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\SIsMember($this);
        $command = $handelClass->getCommand($key, $member);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function sMembers($key)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\SMembers($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function sMove($source, $destination, $member)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\SMove($this);
        $command = $handelClass->getCommand($source, $destination, $member);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function sPop($key)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\SPop($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function sRandMemBer($key, $count = null)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\SRandMemBer($this);
        $command = $handelClass->getCommand($key, $count);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function sRem($key, $member1, ...$members)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\SRem($this);
        $command = $handelClass->getCommand($key, $member1, $members);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function sUnion($key1, ...$keys)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\SUnion($this);
        $command = $handelClass->getCommand($key1, $keys);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function sUnIomStore($destination, $key1, ...$keys)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\SUnIomStore($this);
        $command = $handelClass->getCommand($destination, $key1, $keys);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
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
        $handelClass = new \EasySwoole\Redis\CommandHandel\ZAdd($this);
        $command = $handelClass->getCommand($key, $score1, $member1, $data);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zCard($key)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\ZCard($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zCount($key, $min, $max)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\ZCount($this);
        $command = $handelClass->getCommand($key, $min, $max);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zInCrBy($key, $increment, $member)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\ZInCrBy($this);
        $command = $handelClass->getCommand($key, $increment, $member);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zInTerStore($destination, $numKeys, $key, ...$data)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\ZInTerStore($this);
        $command = $handelClass->getCommand($destination, $numKeys, $key, $data);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zLexCount($key, $min, $max)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\ZLexCount($this);
        $command = $handelClass->getCommand($key, $min, $max);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zRange($key, $start, $stop, $withScores = false)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\ZRange($this);
        $command = $handelClass->getCommand($key, $start, $stop, $withScores);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zRangeByLex($key, $min, $max, ...$data)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\ZRangeByLex($this);
        $command = $handelClass->getCommand($key, $min, $max, $data);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zRangeByScore($key, $min, $max, $withScores = false, ...$data)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\ZRangeByScore($this);
        $command = $handelClass->getCommand($key, $min, $max, $withScores, $data);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zRank($key, $member)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\ZRank($this);
        $command = $handelClass->getCommand($key, $member);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zRem($key, $member, ...$members)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\ZRem($this);
        $command = $handelClass->getCommand($key, $member, $members);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zRemRangeByLex($key, $min, $max)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\ZRemRangeByLex($this);
        $command = $handelClass->getCommand($key, $min, $max);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zRemRangeByRank($key, $start, $stop)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\ZRemRangeByRank($this);
        $command = $handelClass->getCommand($key, $start, $stop);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zRemRangeByScore($key, $min, $max)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\ZRemRangeByScore($this);
        $command = $handelClass->getCommand($key, $min, $max);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zRevRange($key, $start, $stop, $withScores = false)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\ZRevRange($this);
        $command = $handelClass->getCommand($key, $start, $stop, $withScores);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zRevRangeByScore($key, $max, $min, $withScores = false)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\ZRevRangeByScore($this);
        $command = $handelClass->getCommand($key, $max, $min, $withScores);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zRevRank($key, $member)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\ZRevRank($this);
        $command = $handelClass->getCommand($key, $member);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zScore($key, $member)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\ZScore($this);
        $command = $handelClass->getCommand($key, $member);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zUnionStore($destination, $keyNum, $key, ...$data)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\ZUnionStore($this);
        $command = $handelClass->getCommand($destination, $keyNum, $key, $data);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
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
        $handelClass = new \EasySwoole\Redis\CommandHandel\PfAdd($this);
        $command = $handelClass->getCommand($key, $data);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function pfCount($key, ...$keys)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\PfCount($this);
        $command = $handelClass->getCommand($key, $keys);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function pfMerge($deStKey, $sourceKey, ...$sourceKeys)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\PfMerge($this);
        $command = $handelClass->getCommand($deStKey, $sourceKey, $sourceKeys);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    ######################HyperLogLog操作方法######################

    ######################发布订阅操作方法(待测试)######################

    public function pSubscribe($callback, $pattern, ...$patterns)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\PSubscribe($this);
        $command = $handelClass->getCommand($callback, $pattern, $patterns);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function pubSub($subCommand, ...$arguments)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\PubSub($this);
        $command = $handelClass->getCommand($subCommand, $arguments);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function publish($channel, $message)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\Publish($this);
        $command = $handelClass->getCommand($channel, $message);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function pUnSubscribe($pattern, ...$patterns)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\PUnSubscribe($this);
        $command = $handelClass->getCommand($pattern, $patterns);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function subscribe($callback, $channel, ...$channels)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\Subscribe($this);
        $command = $handelClass->getCommand($callback, $channel, $channels);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function unsubscribe($channel, ...$channels)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\Unsubscribe($this);
        $command = $handelClass->getCommand($channel, $channels);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
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
        $handelClass = new \EasySwoole\Redis\CommandHandel\BgReWriteAof($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function bgSave()
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\BgSave($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function clientKill($data): bool
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\ClientKill($this);
        $command = $handelClass->getCommand($data);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function clientList()
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\ClientList($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function clientGetName()
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\ClientGetName($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function clientPause($timeout): bool
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\ClientPause($this);
        $command = $handelClass->getCommand($timeout);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function clientSetName($connectionName): bool
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\ClientSetName($this);
        $command = $handelClass->getCommand($connectionName);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function command()
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\Command($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function commandCount()
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\CommandCount($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function commandGetKeys(...$data)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\CommandGetKeys($this);
        $command = $handelClass->getCommand($data);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function time()
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\Time($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function commandInfo($commandName, ...$commandNames)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\CommandInfo($this);
        $command = $handelClass->getCommand($commandName, $commandNames);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function configGet($parameter)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\ConfigGet($this);
        $command = $handelClass->getCommand($parameter);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function configRewrite(): bool
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\ConfigRewrite($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function configSet($parameter, $value): bool
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\ConfigSet($this);
        $command = $handelClass->getCommand($parameter, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function configResetStat(): bool
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\ConfigResetStat($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function dBSize()
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\DBSize($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function debugObject($key)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\DebugObject($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function debugSegfault()
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\DebugSegfault($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function flushAll(): bool
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\FlushAll($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function flushDb(): bool
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\FlushDb($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function info($section = null)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\Info($this);
        $command = $handelClass->getCommand($section);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function lastSave()
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\LastSave($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function monitor(callable $callback)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\Monitor($this);
        $command = $handelClass->getCommand($callback);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function monitorStop(): void
    {
        $this->monitorStop = true;
    }

    public function role()
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\Role($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function save(): bool
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\Save($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function shutdown()
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\Shutdown($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function slowLog($subCommand, ...$argument)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\SlowLog($this);
        $command = $handelClass->getCommand($subCommand, $argument);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function SYNC()
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\SYNC($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }
    ######################服务器操作方法######################

    ######################geohash操作方法######################
    public function geoAdd($key, $longitude, $latitude, $name, ...$data)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\GeoAdd($this);
        $command = $handelClass->getCommand($key, $longitude, $latitude, $name, $data);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function geoDist($key, $location1, $location2, $unit = 'm')
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\GeoDist($this);
        $command = $handelClass->getCommand($key, $location1, $location2, $unit);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function geoHash($key, $location, ...$locations)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\GeoHash($this);
        $command = $handelClass->getCommand($key, $location, $locations);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function geoPos($key, $location1, ...$locations)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\GeoPos($this);
        $command = $handelClass->getCommand($key, $location1, $locations);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function geoRadius($key, $longitude, $latitude, $radius, $unit = 'm', $withCoord = false, $withDist = false, $withHash = false, $count = null, $sort = null, $storeKey = null, $storeDistKey = null)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\GeoRadius($this);
        $command = $handelClass->getCommand($key, $longitude, $latitude, $radius, $unit, $withCoord, $withDist, $withHash, $count, $sort, $storeKey, $storeDistKey);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function geoRadiusByMember($key, $location, $radius, $unit = 'm', $withCoord = false, $withDist = false, $withHash = false, $count = null, $sort = null, $storeKey = null, $storeDistKey = null)
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\GeoRadiusByMember($this);
        $command = $handelClass->getCommand($key, $location, $radius, $unit, $withCoord, $withDist, $withHash, $count, $sort, $storeKey, $storeDistKey);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }
    ######################geohash操作方法######################


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

    /**
     * @return RedisConfig
     */
    public function getConfig(): RedisConfig
    {
        return $this->config;
    }
}