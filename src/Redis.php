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

    public function set($key, $val): bool
    {
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