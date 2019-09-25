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

    function __construct(array $serverList, float $timeout = 3.0)
    {
        $this->timeout = 3.0;
        $this->nodeInit($serverList);
//        $this->slotInit();

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
            $host = $serverList[0][0];
            $port = $serverList[0][1];
            $client = new Client($host, $port, $this->timeout);
            $result = $this->getServerNodesList($client);
            if ($result == true) {
                break;
            } else {
                $this->errorClientList[] = [$host, $port];
                unset($serverList[$key]);
            }
        }
        $this->slotInit();

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
            return false;
        }
        $list = explode(PHP_EOL, $recv->getData());
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
            $this->nodeList[$node['name']] = $node;
            $this->nodeClientList[$node['name']] = new Client($node['host'], $node['port'], $this->timeout);
        }
        return true;
    }

    /**
     * 槽对应的节点初始化
     * slotInit
     * @return Response|null
     * @author Tioncico
     * Time: 17:31
     */
    protected function slotInit()
    {
        $client = $this->getClient();
        $client->connect();
        //获取节点信息
        $client->sendCommand(['cluster', 'slots']);
        $recv = $client->recv();
        var_dump($recv->getData());
        foreach ($recv->getData() as $slot) {

            $this->nodeList[$slot[2][2]]['slot'][0] = $slot[0];
            $this->nodeList[$slot[2][2]]['slot'][1] = $slot[1];
        }
        return $recv;
    }

    /**
     * 发送命令
     * sendCommand
     * @param array $commandList
     * @return mixed
     * @author Tioncico
     * Time: 16:38
     */
    public function sendCommand(array $commandList)
    {
        $result = $this->getClient()->sendCommand($commandList);
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
    function recv(): ?Response
    {
        $result = $this->getClient()->recv();
        $command = array_shift($this->lastCommandLog);
        if ($result->getErrorType() == 'MOVED') {
            $nodeId = $this->getMoveNodeId($result);
            $client = $this->getClient($nodeId);
            var_dump($command);
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
        if ($nodeKey == null) {
            $client = $this->nodeClientList[array_rand($this->nodeClientList)];
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
        $slotId = $data[1];
        $nodeId = $this->getSlotNodeId($slotId);
        if ($nodeId == null) {
//            throw new ClusterClientException('不存在节点:'.$response->getMsg());
//            $server = explode(':', $data[2]);
//            $this->nodeList[$nodeId] = new Client($server[0], $server[1], $this->timeout);
        }
        var_dump($nodeId);
        return $nodeId;
    }

    protected function getSlotNodeId($slotId)
    {
        foreach ($this->nodeList as $key => $node) {
            if (empty($node['slot'])) {
                continue;
            }
            var_dump($node['slot'][0], $node['slot'][1], $slotId);
            if ($node['slot'][0] >= $slotId && $node['slot'][1] <= $slotId) {
                return $key;
            }
        }
        return null;
    }
}