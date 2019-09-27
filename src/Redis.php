<?php


namespace EasySwoole\Redis;


use EasySwoole\Redis\Config\RedisConfig;

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

    public function set($key, $val,$expireTime=null): bool
    {
        $command = [$key,$val];
        if ($expireTime!=null&&$expireTime>0){
            $command[] = 'EX '.$expireTime;
        }

        $data = ['set', $key, $val];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv===null){
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
        if ($recv===null){
            return false;
        }
        return $recv->getData();
    }

    public function del($key)
    {
        $data = ['del', $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv===null){
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
        if ($recv===null){
            return false;
        }
        return $recv->getData();
    }

    public function expire($key,$expireTime=60)
    {
        $data = ['expire', $key,$expireTime];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv===null){
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
        if ($recv===null){
            return false;
        }
        return $recv->getData();
    }

    public function incrBy($key,$value)
    {
        $data = ['incrby', $key,$value];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv===null){
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
        if ($recv===null){
            return false;
        }
        return $recv->getData();
    }

    public function decrBy($key,$value)
    {
        $data = ['decrby', $key,$value];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv===null){
            return false;
        }
        return $recv->getData();
    }










    public function hDel($key,...$field){
        $data = ['hdel', $key,$field];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv===null){
            return false;
        }
        return $recv->getData();
    }

    public function hMGet($key,...$field){
        $data = ['hmget ', $key,$field];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv===null){
            return false;
        }
        return $recv->getData();
    }

    public function hExists($key,$field){
        $data = ['hexists', $key,$field];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv===null){
            return false;
        }
        return $recv->getData();
    }

    public function hMSet($key,$data){
        $data = ['hmset', $key,$data];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv===null){
            return false;
        }
        return $recv->getData();
    }

    public function hSet($key,$field,$value){
        $data = ['hset', $key,$field,$value];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv===null){
            return false;
        }
        return $recv->getData();
    }

    public function hVals($key){
        $data = ['hvals', $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv===null){
            return false;
        }
        return $recv->getData();
    }

    public function hGetAll($key){
        $data = ['hgetall', $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv===null){
            return false;
        }
        return $recv->getData();
    }

    public function hKeys($key){
        $data = ['hkeys', $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv===null){
            return false;
        }
        return $recv->getData();
    }

    public function hLen($key){
        $data = ['hlen', $key];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv===null){
            return false;
        }
        return $recv->getData();
    }

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
        while ($this->tryConnectTimes <= $this->config->getReconnectTimes()) {
            if ($this->connect()) {
                $recv = $this->client->recv($this->config->getTimeout());
                if ($recv->getStatus() == $recv::STATUS_ERR) {
                    $this->errorType = $recv->getErrorType();
                    $this->errorMsg = $recv->getMsg();
                    return null;
                } elseif ($recv->getStatus() == $recv::STATUS_OK) {
                    return $recv;
                } elseif ($recv->getStatus() == $recv::STATUS_TIMEOUT) {
                    /*
                     * 接收失败的时候，强制切断链接进入重连发送逻辑
                     */
                    $this->client->close();;
                }
            }
            $this->lastSocketError = $this->client->socketError();
            $this->lastSocketErrno = $this->client->socketErrno();
            $this->tryConnectTimes++;
        }
        return null;
    }

    protected function reset()
    {
        $this->tryConnectTimes = 0;
        $this->lastSocketErrno = 0;
        $this->lastSocketError = '';
        $this->errorMsg = '';
        $this->errorType = '';
    }
}