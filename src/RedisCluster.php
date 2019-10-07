<?php


namespace EasySwoole\Redis;


use EasySwoole\Redis\Config\RedisClusterConfig;
use EasySwoole\Redis\Exception\RedisClusterException;

class RedisCluster extends Redis
{
    /**
     * @var RedisClusterConfig $config
     */
    protected $config;
    /**
     * 节点客户端列表
     * @var $nodeClientList Client[]
     */
    protected $nodeClientList = [];

    /**
     * @var array 节点列表
     */
    protected $nodeList = [];

    protected $lastCommandLog = [];


    protected $errorClientList = [];


    public function __construct(RedisClusterConfig $config)
    {
        $this->config = $config;
        $this->nodeInit();
    }

    ######################集群客户端连接方法######################
    public function clientConnect(ClusterClient $client, float $timeout = null): bool
    {
        if ($client->isConnected()) {
            return true;
        }
        if ($timeout === null) {
            $timeout = $this->config->getTimeout();
        }
        $client->setIsConnected($client->connect($timeout));
        if ($client->isConnected() && !empty($this->config->getAuth())) {
            if (!$this->auth($this->config->getAuth())) {
                $client->setIsConnected(false);
                throw new RedisClusterException("auth to redis host {$this->config->getHost()}:{$this->config->getPort()} fail");
            }
        }
        return $client->isConnected();
    }


    function clientDisconnect(ClusterClient $client)
    {
        if ($client->isConnected()) {
            $client->setIsConnected(false);
            $this->lastSocketError = $client->socketError();
            $this->lastSocketErrno = $client->socketErrno();
            $client->close();
        }
    }
    ######################服务器连接方法######################

    ######################集群类方法######################
    /**
     * 初始化节点
     * nodeInit
     * @throws RedisClusterException
     * @author tioncico
     * Time: 下午9:00
     */
    protected function nodeInit()
    {
        $serverList = $this->config->getServerList();
        //第一次循环,使用可用的服务配置获取服务端的节点,获取到之后直接退出循环
        foreach ($serverList as $key => $server) {
            $host = $server[0];
            $port = $server[1];
            $client = new Client($host, $port);
            $this->clientConnect($client);
            $nodeList = $this->getServerNodesList($client);
            if ($nodeList === null) {
                unset($serverList[$key]);
                continue;
            }
            break;
        }
        if (empty($serverList) || empty($nodeList)) {
            throw new RedisClusterException('服务器配置错误');
        }
        $this->nodeListInit($nodeList);
    }

    /**
     * 获取服务端节点列表
     * getServerNodesList
     * @param Client $client
     * @return array|null
     * @author tioncico
     * Time: 下午8:59
     */
    protected function getServerNodesList(Client $client)
    {
        //获取节点信息
        $client->sendCommand(['cluster', 'nodes']);
        $recv = $client->recv();
        if ($recv->getStatus() != 0) {
            return null;
        }
        $list = explode(PHP_EOL, $recv->getData());
        $nodeList = [];
        foreach ($list as $serverData) {
            $data = explode(' ', $serverData);
            if (empty($data[0])) {
                continue;
            }
            list($host, $port) = explode(':', explode('@', $data[1])[0]);
            $node = [
                'name'      => $data[0],
                'host'      => $host,
                'port'      => $port,
                'flags'     => $data[2],
                'connected' => $data[7],
                'slot'      => isset($data[8]) ? explode('-', $data[8]) : [],
            ];
            $nodeList[$node['name']] = $node;
        }
        return $nodeList;
    }

    protected function nodeListInit($nodeList)
    {
        foreach ($nodeList as $node) {
            if (isset($this->nodeList[$node['name']])) {
                $this->nodeList[$node['name']] = $node;
            } else {
                $this->nodeList[$node['name']] = $node;
                $this->nodeClientList[$node['name']] = new Client($node['host'], $node['port']);
            }
        }
    }

    protected function tryConnectServerList()
    {
        foreach ($this->getNodeClientList() as $client) {
            $result = $client->connect();
            if ($result === false) {
                continue;
            }
            $nodeList = $this->getServerNodesList($client);
            break;
        }

        if (empty($nodeList)) {
            throw new RedisClusterException('节点服务器获取失败');
        }
        $this->nodeListInit($nodeList);
    }

    /**
     * 获取节点客户端
     * getClient
     * @param  $nodeKey
     * @return Client
     * @author Tioncico
     * Time: 16:39
     */
    protected function getClient($nodeKey = null): Client
    {
        if ($nodeKey == null || !isset($this->nodeClientList[$nodeKey])) {
            foreach ($this->nodeClientList as $node) {
                $client = $node;
            }
        } else {
            $client = $this->nodeClientList[$nodeKey];
        }
        $this->clientConnect($client);
        return $client;
    }

    /**
     * 获取move的节点id
     * getMoveNodeId
     * @param Response $response
     * @return int|string|null
     * @throws RedisClusterException
     * @author tioncico
     * Time: 下午9:00
     */
    protected function getMoveNodeId(Response $response)
    {
        $data = explode(' ', $response->getMsg());
        $nodeId = $this->getSlotNodeId($data[1]);
        if ($nodeId == null) {
            throw new RedisClusterException('不存在节点:' . $nodeId);
        }
        return $nodeId;
    }

    protected function getSlotNodeId($slotId)
    {
        foreach ($this->nodeList as $key => $node) {
            if (empty($node['slot'])) {
                continue;
            }
            if ($node['slot'][0] <= $slotId && $node['slot'][1] >= $slotId) {
                if (strpos($node['flags'], 'master') !== false) {
                    return $key;
                }
            }
        }
        return null;
    }

    /**
     * @return Client[]
     */
    public function getNodeClientList(): array
    {
        return $this->nodeClientList;
    }

    /**
     * @return array
     */
    public function getNodeList(): array
    {
        return $this->nodeList;
    }
    ######################集群类方法######################

    ###################### 发送接收tcp流数据 ######################
    protected function sendCommandByClient(array $commandList, ClusterClient $client): bool
    {
        while ($this->tryConnectTimes <= $this->config->getReconnectTimes()) {
            if ($this->clientConnect($client)) {
                if ($client->sendCommand($commandList)) {
                    $this->reset();
                    return true;
                }
            }
            //节点断线处理
            $this->tryConnectServerList();
            $this->disconnect();
            $this->tryConnectTimes++;
        }
        /*
         * 链接超过重连次数，应该抛出异常
         */
        throw new RedisClusterException("connect to redis host {$client->getHost()}:{$client->getPort()} fail after retry {$this->tryConnectTimes} times");
    }

    protected function sendCommand(array $commandList, $nodeId = null): bool
    {
        $client = $this->getClient($nodeId);
        return $this->sendCommandByClient($commandList,$client);
    }

    public function recv($timeout = null): ?Response
    {
        $recv = $this->client->recv($timeout ?? $this->config->getTimeout());
        if ($recv->getStatus() == $recv::STATUS_ERR) {
            $this->errorType = $recv->getErrorType();
            $this->errorMsg = $recv->getMsg();
            //未登录
            if ($this->errorType == 'NOAUTH') {
                throw new RedisClusterException($recv->getMsg());
            }
        } elseif ($recv->getStatus() == $recv::STATUS_OK) {
            return $recv;
        } elseif ($recv->getStatus() == $recv::STATUS_TIMEOUT) {
            $this->disconnect();
        }
        return null;
    }
    ###################### 发送接收tcp流数据 ######################
}