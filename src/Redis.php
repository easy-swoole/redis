<?php


namespace EasySwoole\Redis;


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

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function connect(float $timeout = null):bool
    {
        if($this->isConnected){
            return true;
        }
        if($timeout === null){
            $timeout = $this->config->getTimeout();
        }
        if($this->client == null){
            $this->client = new Client($this->config->getHost(),$this->config->getPort());
        }
        $this->isConnected = $this->client->connect($timeout);
        return $this->isConnected;
    }

    public function set($key,$val):bool
    {
        $data = ['set',$key,$val];
        if(!$this->sendCommand($data)){
            return false;
        }
        /*
         * 执行recv解析,并赋值errorType与errorMsg
         */
        return true;
    }

    protected function sendCommand(array $com):bool
    {
        while ($this->tryConnectTimes <= $this->config->getReconnectTimes()){
            if($this->connect()){
                if($this->client->sendCommand($com)){
                    $this->reset();;
                    return true;
                }else{
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

    protected function reset()
    {
        $this->tryConnectTimes = 0;
        $this->lastSocketErrno = 0;
        $this->lastSocketError = '';
        $this->errorMsg = '';
        $this->errorType = '';
    }
}