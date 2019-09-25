<?php


namespace EasySwoole\Redis;


use EasySwoole\Redis\Exception\ClusterClientException;

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
    protected $tryTimes=3;

    function __construct(array $serverList, float $timeout = 3.0)
    {
        $this->timeout = $timeout;
        $this->nodeInit($serverList);
    }

    /**
     * 初始化节点
     * nodeInit
     * @param $serverList
     * @author Tioncico
     * Time: 16:38
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
            throw new ClusterClientException('服务器配置错误');
        }
        foreach ($nodeList as $node) {
            $this->nodeList[$node['name']] = $node;
            $this->nodeClientList[$node['name']] = new Client($node['host'], $node['port'], $this->timeout);
        }
    }

    /**
     * 获取服务端节点列表
     * getServerNodesList
     * @param Client $client
     * @return bool
     * @author Tioncico
     * Time: 17:32
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
                'name'  => $data[0],
                'host'  => $host,
                'port'  => $port,
                'flags' => $data[2]
            ];
            $nodeList[$node['name']] = $node;
        }
        return $nodeList;
    }


    /**
     * 发送命令
     * sendCommand
     * @param array $commandList
     * @return mixed
     * @author Tioncico
     * Time: 16:38
     */
    public function sendCommand(array $commandList, $nodeId = null,$times=0)
    {
        if ($times>=$this->tryTimes){
            throw new ClusterClientException('超过最大重试次数');
        }
        $client = $this->getClient($nodeId);
        $result = $client->sendCommand($commandList);
        $commandList['times']=$times+1;
        array_push($this->lastCommandLog, $commandList);
        return $result;
    }

    /**
     * 接收数据
     * recv
     * @return Response|null
     * @author Tioncico
     * Time: 16:38
     */
    function recv($nodeId = null,$times=0): ?Response
    {
        if ($times>=$this->tryTimes){
            throw new ClusterClientException('超过最大重试次数');
        }
        $client = $this->getClient($nodeId);
        $result = $client->recv();
        $command = array_shift($this->lastCommandLog);
        if ($result->getErrorType() == 'MOVED') {
            $nodeId = $this->getMoveNodeId($result);
            $sendTimes = $command['times'];
            unset($command['times']);
            $this->sendCommand($command, $nodeId,$sendTimes);
            $result = $this->recv($nodeId,$times+1);
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
        if ($nodeKey == null) {
            $client = reset($this->nodeClientList);
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
     * @return mixed
     * @author Tioncico
     * Time: 16:39
     */
    protected function getMoveNodeId(Response $response)
    {
        $data = explode(' ', $response->getMsg());
        $nodeId = $this->getSlotNodeId($data[2]);
        if ($nodeId == null) {
            throw new ClusterClientException('不存在节点:' . $nodeId);
        }
        return $nodeId;
    }

    protected function getSlotNodeId($data)
    {
        list($host, $port) = explode(':', $data);
        foreach ($this->nodeList as $key => $node) {
            if ($node['host'] == $host && $node['port'] == $port) {
                return $key;
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