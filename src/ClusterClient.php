<?php


namespace EasySwoole\Redis;


use EasySwoole\Redis\Exception\RedisClusterException;

class ClusterClient
{
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
    protected $timeout = 3.0;
    protected $errorClientList = [];
    /**
     * @var bool 是否同步节点
     */
    protected $isSyncNode = false;
    protected $tryTimes = 3;

    function __construct(array $serverList, float $timeout = 3.0)
    {
        $this->timeout = $timeout;
        $this->nodeInit($serverList);
    }

    /**
     * 初始化节点
     * nodeInit
     * @param $serverList
     * @throws RedisClusterException
     * @author tioncico
     * Time: 下午9:00
     */
    protected function nodeInit($serverList)
    {
        //第一次循环,使用可用的服务配置获取服务端的节点,获取到之后直接退出循环
        foreach ($serverList as $key => $server) {
            $host = $server[0];
            $port = $server[1];
            $client = new Client($host, $port, $this->timeout);
            $nodeList = $this->getServerNodesList($client);
            if ($nodeList === null) {
                $this->errorClientList[] = [$host, $port];
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

    protected function nodeListInit($nodeList)
    {
        foreach ($nodeList as $node) {
            if (isset($this->nodeList[$node['name']])) {
                $this->nodeList[$node['name']] = $node;
            } else {
                $this->nodeList[$node['name']] = $node;
                $this->nodeClientList[$node['name']] = new Client($node['host'], $node['port'], $this->timeout);
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
     * 获取服务端节点列表
     * getServerNodesList
     * @param Client $client
     * @return array|null
     * @author tioncico
     * Time: 下午8:59
     */
    protected function getServerNodesList(Client $client)
    {
        $client->connect();
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


    /**
     * 发送命令
     * sendCommand
     * @param array $commandList
     * @param null  $nodeId
     * @param int   $times
     * @return mixed
     * @throws RedisClusterException
     * @author tioncico
     * Time: 下午8:58
     */
    public function sendCommand(array $commandList, $nodeId = null, $times = 0)
    {
        if ($times >= $this->tryTimes) {
            throw new RedisClusterException('超过最大重试次数');
        }
        $client = $this->getClient($nodeId);

        $result = $client->sendCommand($commandList);
        if ($result === false) {
            //节点断线处理
            $this->tryConnectServerList();
            return $this->sendCommand($commandList, $nodeId, $times + 1);
        }
        $commandList['times'] = $times + 1;
        array_push($this->lastCommandLog, $commandList);
        return $result;
    }

    /**
     * 接收数据
     * recv
     * @param null $nodeId
     * @param int  $times
     * @return Response|null
     * @throws RedisClusterException
     * @author tioncico
     * Time: 下午8:58
     */
    function recv($nodeId = null, $times = 0): ?Response
    {
        if ($times >= $this->tryTimes) {
            throw new RedisClusterException('超过最大重试次数');
        }
        $client = $this->getClient($nodeId);
        $result = $client->recv();
        $command = array_shift($this->lastCommandLog);
        if ($result->getStatus() === $result::STATUS_TIMEOUT) {
            //节点断线处理
            $this->tryConnectServerList();
            return $this->recv($nodeId, $times + 1);
        }

        //节点转移客户端处理
        if ($result->getErrorType() == 'MOVED') {
            $nodeId = $this->getMoveNodeId($result);
            $client = $this->getClient($nodeId);
            unset($command['times']);
            //只处理一次moved,如果出错则不再处理
            $client->sendCommand($command);
            $result = $client->recv();
        }
        return $result;
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

        $client->connect();
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
            if (empty($node['slot'])){
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
}