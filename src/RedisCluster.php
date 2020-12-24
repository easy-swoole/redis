<?php


namespace EasySwoole\Redis;


use EasySwoole\Redis\CommandHandel\Auth;
use EasySwoole\Redis\CommandHandel\ClusterCommand\ClusterAddSlots;
use EasySwoole\Redis\CommandHandel\ClusterCommand\ClusterCountFailureReports;
use EasySwoole\Redis\CommandHandel\ClusterCommand\ClusterCountKeySinSlot;
use EasySwoole\Redis\CommandHandel\ClusterCommand\ClusterDelSlots;
use EasySwoole\Redis\CommandHandel\ClusterCommand\ClusterFailOver;
use EasySwoole\Redis\CommandHandel\ClusterCommand\ClusterForget;
use EasySwoole\Redis\CommandHandel\ClusterCommand\ClusterGetKeySinSlot;
use EasySwoole\Redis\CommandHandel\ClusterCommand\ClusterInfo;
use EasySwoole\Redis\CommandHandel\ClusterCommand\ClusterKeySlot;
use EasySwoole\Redis\CommandHandel\ClusterCommand\ClusterMeet;
use EasySwoole\Redis\CommandHandel\ClusterCommand\ClusterNodes;
use EasySwoole\Redis\CommandHandel\ClusterCommand\ClusterReplicate;
use EasySwoole\Redis\CommandHandel\ClusterCommand\ClusterReset;
use EasySwoole\Redis\CommandHandel\ClusterCommand\ClusterSaveConfig;
use EasySwoole\Redis\CommandHandel\ClusterCommand\ClusterSetConfigEpoch;
use EasySwoole\Redis\CommandHandel\ClusterCommand\ClusterSetSlot;
use EasySwoole\Redis\CommandHandel\ClusterCommand\ClusterSlaves;
use EasySwoole\Redis\CommandHandel\ClusterCommand\ClusterSlots;
use EasySwoole\Redis\CommandHandel\ClusterCommand\Readonly;
use EasySwoole\Redis\CommandHandel\ClusterCommand\Readwrite;
use EasySwoole\Redis\CommandHandel\Del;
use EasySwoole\Redis\CommandHandel\ExecPipe;
use EasySwoole\Redis\CommandHandel\MGet;
use EasySwoole\Redis\CommandHandel\MSet;
use EasySwoole\Redis\CommandHandel\MSetNx;
use EasySwoole\Redis\CommandHandel\Ping;
use EasySwoole\Redis\CommandHandel\Unlink;
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
    /**
     * @var $defaultClient ClusterClient
     */
    protected $defaultClient = null;

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
            if (!$this->clientAuth($client, $this->config->getAuth())) {
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

    public function connect(float $timeout = null): bool
    {
        $client = $this->getDefaultClient();
        return $this->clientConnect($client, $timeout);
    }

    public function disconnect()
    {
        $client = $this->getDefaultClient();
        $this->clientDisconnect($client);
    }

    public function pingAll()
    {
        //获取所有客户端
        $result = [];
        /**
         * @var $client ClusterClient
         */
        foreach ($this->nodeClientList as $key => $client) {
            $handelClass = new Ping($this);
            $command = $handelClass->getCommand();
            if (!$this->sendCommandByClient($command, $client)) {
                $result[$key] = false;
                continue;
            }
            $recv = $this->recvByClient($client);
            if ($recv === null) {
                $result[$key] = false;
                continue;
            }
            $result[$key] = $handelClass->getData($recv);
        }
        return $result;
    }
    ######################服务器连接方法######################

    ######################集群处理方法######################
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
            $client = new ClusterClient($host, $port,$this->config->getPackageMaxLength());
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
     * @param ClusterClient $client
     * @return bool|string
     * @throws RedisClusterException
     * @author Tioncico
     * Time: 10:34
     */
    protected function getServerNodesList(ClusterClient $client)
    {
        $handelClass = new ClusterNodes($this);
        $command = $handelClass->getCommand();
        if (!$this->sendCommandByClient($command, $client)) {
            return false;
        }
        $recv = $this->recvByClient($client);
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    /**
     * nodeListInit
     * @param $nodeList
     * @throws RedisClusterException
     * @author Tioncico
     * Time: 10:59
     */
    protected function nodeListInit($nodeList)
    {
        $nodeListArr = [];
        foreach ($nodeList as $node) {
            if (isset($this->nodeList[$node['name']])) {
                $this->nodeList[$node['name']] = $node;
            } else {
                $this->nodeList[$node['name']] = $node;
                $clusterClient = new ClusterClient($node['host'], $node['port']);
                //尝试连接客户端
//                $this->clientConnect($clusterClient);
                $this->nodeClientList[$node['name']] = $clusterClient;
            }
            $nodeListArr[$node['name']] = $node;
        }
        $this->nodeList = $nodeListArr;
    }

    public function tryConnectServerList()
    {
        foreach ($this->getNodeClientList() as $client) {
            $result = $this->clientConnect($client);
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
    public function getClient($nodeKey = null): ClusterClient
    {
        //当key为null,并且defaultClient存在并且defaultClient已连接时,直接返回
        if ($nodeKey == null && $this->defaultClient instanceof ClusterClient && $this->defaultClient->isConnected()) {
            return $this->defaultClient;
        }
        //当key为null,或者nodeClient未找到该客户端时
        if ($nodeKey == null || !isset($this->nodeClientList[$nodeKey])) {
            //取出第一个客户端
            foreach ($this->nodeClientList as $node) {
                $client = $node;
                break;
            }
        } else {
            $client = $this->nodeClientList[$nodeKey];
        }
        return $client;
    }

    public function getClientBySlotId($slotId)
    {
        $nodeId = $this->getSlotNodeId($slotId);
        if ($nodeId === null) {
            throw new RedisClusterException('不存在节点:' . $nodeId);
        }
        return $this->getClient($nodeId);
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
    public function getMoveNodeId(Response $response)
    {
        $data = explode(' ', $response->getMsg());
        $nodeId = $this->getSlotNodeId($data[1]);
        if ($nodeId === null) {
            throw new RedisClusterException('不存在节点:' . $nodeId);
        }
        return $nodeId;
    }

    public function getSlotNodeId($slotId)
    {
        foreach ($this->nodeList as $key => $node) {
            if (empty($node['slotList'])) {
                continue;
            }

            if (strpos($node['flags'], 'master') === false) {
                continue;
            }
            foreach ($node['slotList'] as $slot){
                if ($slot[0] <= $slotId && $slot[1] >= $slotId) {
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

    public function clientAuth(ClusterClient $client, $password): bool
    {
        $handelClass = new Auth($this);
        $command = $handelClass->getCommand($password);

        if (!$this->sendCommandByClient($command, $client)) {
            return false;
        }
        $recv = $this->recvByClient($client);
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    /**
     * setDefaultClient
     * @param ClusterClient $defaultClient
     * @author Tioncico
     * Time: 17:25
     */
    public function setDefaultClient(ClusterClient $defaultClient)
    {
        $this->defaultClient = $defaultClient;
    }

    /**
     * getDefaultClient
     * @return ClusterClient|null
     * @throws RedisClusterException
     * @author Tioncico
     * Time: 9:30
     */
    public function getDefaultClient(): ?ClusterClient
    {
        if ($this->defaultClient === null) {
            $this->setDefaultClient($this->getClient());
        }
        return $this->defaultClient;
    }


    ######################集群处理方法######################

    ###################### redis集群方法 ######################

    public function clusterNodes()
    {
        $client = $this->getClient();
        $handelClass = new ClusterNodes($this);
        $command = $handelClass->getCommand();
        if (!$this->sendCommandByClient($command, $client)) {
            return false;
        }
        $recv = $this->recvByClient($client);
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function clusterAddSlots($slots)
    {
        $client = $this->getClient();
        $handelClass = new ClusterAddSlots($this);
        $command = $handelClass->getCommand($slots);
        if (!$this->sendCommandByClient($command, $client)) {
            return false;
        }
        $recv = $this->recvByClient($client);
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function clusterCountFailureReports($nodeId)
    {
        $client = $this->getClient();
        $handelClass = new ClusterCountFailureReports($this);
        $command = $handelClass->getCommand($nodeId);
        if (!$this->sendCommandByClient($command, $client)) {
            return false;
        }
        $recv = $this->recvByClient($client);
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function clusterCountKeySinSlot($slot)
    {
        $client = $this->getClient();
        $handelClass = new ClusterCountKeySinSlot($this);
        $command = $handelClass->getCommand($slot);
        if (!$this->sendCommandByClient($command, $client)) {
            return false;
        }
        $recv = $this->recvByClient($client);
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function clusterDelSlots($slot)
    {
        $client = $this->getClient();
        $handelClass = new ClusterDelSlots($this);
        $command = $handelClass->getCommand($slot);
        if (!$this->sendCommandByClient($command, $client)) {
            return false;
        }
        $recv = $this->recvByClient($client);
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function clusterFailOver($option = null)
    {
        $client = $this->getClient();
        $handelClass = new ClusterFailOver($this);
        $command = $handelClass->getCommand($option);
        if (!$this->sendCommandByClient($command, $client)) {
            return false;
        }
        $recv = $this->recvByClient($client);
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function clusterForget($nodeId)
    {
        $client = $this->getClient();
        $handelClass = new ClusterForget($this);
        $command = $handelClass->getCommand($nodeId);
        if (!$this->sendCommandByClient($command, $client)) {
            return false;
        }
        $recv = $this->recvByClient($client);
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function clusterGetKeySinSlot($slot, $count)
    {
        $client = $this->getClient();
        $handelClass = new ClusterGetKeySinSlot($this);
        $command = $handelClass->getCommand($slot, $count);
        if (!$this->sendCommandByClient($command, $client)) {
            return false;
        }
        $recv = $this->recvByClient($client);
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function clusterInfo()
    {
        $client = $this->getClient();
        $handelClass = new ClusterInfo($this);
        $command = $handelClass->getCommand();
        if (!$this->sendCommandByClient($command, $client)) {
            return false;
        }
        $recv = $this->recvByClient($client);
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function clusterKeySlot($key)
    {
        $client = $this->getClient();
        $handelClass = new ClusterKeySlot($this);
        $command = $handelClass->getCommand($key);
        if (!$this->sendCommandByClient($command, $client)) {
            return false;
        }
        $recv = $this->recvByClient($client);
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function clusterMeet($ip, $port)
    {
        $client = $this->getClient();
        $handelClass = new ClusterMeet($this);
        $command = $handelClass->getCommand($ip, $port);
        if (!$this->sendCommandByClient($command, $client)) {
            return false;
        }
        $recv = $this->recvByClient($client);
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function clusterReplicate($nodeId)
    {
        $client = $this->getClient();
        $handelClass = new ClusterReplicate($this);
        $command = $handelClass->getCommand($nodeId);
        if (!$this->sendCommandByClient($command, $client)) {
            return false;
        }
        $recv = $this->recvByClient($client);
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function clusterReset($option = null)
    {
        $client = $this->getClient();
        $handelClass = new ClusterReset($this);
        $command = $handelClass->getCommand($option);
        if (!$this->sendCommandByClient($command, $client)) {
            return false;
        }
        $recv = $this->recvByClient($client);
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function clusterSaveConfig()
    {
        $client = $this->getClient();
        $handelClass = new ClusterSaveConfig($this);
        $command = $handelClass->getCommand();
        if (!$this->sendCommandByClient($command, $client)) {
            return false;
        }
        $recv = $this->recvByClient($client);
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function clusterSetConfigEpoch($configEpoch)
    {
        $client = $this->getClient();
        $handelClass = new ClusterSetConfigEpoch($this);
        $command = $handelClass->getCommand($configEpoch);
        if (!$this->sendCommandByClient($command, $client)) {
            return false;
        }
        $recv = $this->recvByClient($client);
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function clusterSetSlot($slot, $subCommand, $nodeId = null)
    {
        $client = $this->getClient();
        $handelClass = new ClusterSetSlot($this);
        $command = $handelClass->getCommand($slot, $subCommand, $nodeId);
        if (!$this->sendCommandByClient($command, $client)) {
            return false;
        }
        $recv = $this->recvByClient($client);
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function clusterSlaves($nodeId)
    {
        $client = $this->getClient();
        $handelClass = new ClusterSlaves($this);
        $command = $handelClass->getCommand($nodeId);
        if (!$this->sendCommandByClient($command, $client)) {
            return false;
        }
        $recv = $this->recvByClient($client);
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function clusterSlots()
    {
        $client = $this->getClient();
        $handelClass = new ClusterSlots($this);
        $command = $handelClass->getCommand();
        if (!$this->sendCommandByClient($command, $client)) {
            return false;
        }
        $recv = $this->recvByClient($client);
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function readonly()
    {
        $client = $this->getClient();
        $handelClass = new Readonly($this);
        $command = $handelClass->getCommand();
        if (!$this->sendCommandByClient($command, $client)) {
            return false;
        }
        $recv = $this->recvByClient($client);
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function readwrite()
    {
        $client = $this->getClient();
        $handelClass = new Readwrite($this);
        $command = $handelClass->getCommand();
        if (!$this->sendCommandByClient($command, $client)) {
            return false;
        }
        $recv = $this->recvByClient($client);
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    ###################### redis集群方法 ######################

    ###################### redis集群兼容方法 ######################
    public function del(...$keys): ?int
    {
        $handelClass = new Del($this);
        $key = array_shift($keys);
        if (is_array($key)) {
            $keyList = $key;
        } else {
            $keyList = array_merge([$key], $keys);
        }
        $result = 0;
        foreach ($keyList as $k) {
            $slotId = CrcHash::getRedisSlot($k);
            $client = $this->getClientBySlotId($slotId);
            $command = $handelClass->getCommand($k);
            if (!$this->sendCommandByClient($command, $client)) {
                continue;
            }
            $recv = $this->recvByClient($client);
            if ($recv === null) {
                continue;
            }
            $result += $handelClass->getData($recv);
        }
        return $result;
    }

    public function unlink(...$keys): ?int
    {
        $handelClass = new Unlink($this);
        $key = array_shift($keys);
        if (is_array($key)) {
            $keyList = $key;
        } else {
            $keyList = array_merge([$key], $keys);
        }
        $result = 0;
        foreach ($keyList as $k) {
            $slotId = CrcHash::getRedisSlot($k);
            $client = $this->getClientBySlotId($slotId);
            $command = $handelClass->getCommand($k);
            if (!$this->sendCommandByClient($command, $client)) {
                continue;
            }
            $recv = $this->recvByClient($client);
            if ($recv === null) {
                continue;
            }
            $result += $handelClass->getData($recv);
        }
        return $result;
    }

    public function mSet($data): bool
    {
        $handelClass = new MSet($this);
        foreach ($data as $k => $value) {
            $kvData = [];
            $kvData[$k] = $value;
            $slotId = CrcHash::getRedisSlot($k);
            $client = $this->getClientBySlotId($slotId);
            $command = $handelClass->getCommand($kvData);
            if (!$this->sendCommandByClient($command, $client)) {
                continue;
            }
            $recv = $this->recvByClient($client);
            if ($recv === null) {
                continue;
            }
        }
        return true;
    }

    public function mGet($keys)
    {
        $handelClass = new MGet($this);
        $result = [];
        if (is_string($keys)) {
            $keys = [$keys];
        }
        foreach ($keys as $k => $value) {
            $slotId = CrcHash::getRedisSlot($k);
            $client = $this->getClientBySlotId($slotId);
            $command = $handelClass->getCommand($value);
            if (!$this->sendCommandByClient($command, $client)) {
                $result[$k] = false;
                continue;
            }
            $recv = $this->recvByClient($client);
            if ($recv === null) {
                $result[$k] = false;
                continue;
            }
            $result[$k] = $handelClass->getData($recv)[0];
        }
        return $result;
    }

    public function mSetNx($data)
    {
        $handelClass = new MSetNx($this);
        $result = [];
        foreach ($data as $k => $value) {
            $slotId = CrcHash::getRedisSlot($k);
            $client = $this->getClientBySlotId($slotId);

            $kvData = [];
            $kvData[$k] = $value;
            $command = $handelClass->getCommand($kvData);
            if (!$this->sendCommandByClient($command, $client)) {
                $result[] = false;
                continue;
            }
            $recv = $this->recvByClient($client);
            if ($recv === null) {
                $result[] = false;
                continue;
            }
            $result[] = $handelClass->getData($recv);
        }
        return $result;
    }
    ###################### redis集群兼容方法 ######################


    ######################redis集群管道兼容方法######################

    public function execPipe(?ClusterClient $client = null)
    {
        $handelClass = new ExecPipe($this);
        $commandData = $handelClass->getCommand();
        $client = $client ?? $this->getDefaultClient();
        //必须默认客户端,否则recv获取不到数据
        $this->setDefaultClient($client);
        //发送原始tcp数据
        if (!$client->send($commandData)) {
            return false;
        }
        //模拟获取服务器数据,不实际执行
        $recv = new Response();
        $recv->setStatus($recv::STATUS_OK);
        $recv->setData(true);
        return $handelClass->getData($recv);
    }

    ######################redis集群管道兼容方法######################

    ###################### 发送接收tcp流数据 ######################
    public function sendCommandByClient(array $commandList, ClusterClient $client): bool
    {
        $client = $client;
        //重置重试次数
        $this->tryConnectTimes = 0;
        while ($this->tryConnectTimes <= $this->config->getReconnectTimes()) {
            if ($this->clientConnect($client)) {
                if ($client->sendCommand($commandList)) {
                    $this->reset();
                    array_push($this->lastCommandLog, $commandList);
                    return true;
                }
            }
            //节点断线处理
            $this->tryConnectServerList();
            $this->clientDisconnect($client);
            $this->tryConnectTimes++;
            $client = $this->getClient();
        }
        /*
         * 链接超过重连次数，应该抛出异常
         */
        throw new RedisClusterException("connect to redis host {$client->getHost()}:{$client->getPort()} fail after retry {$this->tryConnectTimes} times");
    }

    public function recvByClient(ClusterClient $client, $timeout = null)
    {
        $command = array_shift($this->lastCommandLog);
        $result = $client->recv($timeout ?? $this->config->getTimeout());
        //节点转移客户端处理
        if ($result->getErrorType() == 'MOVED') {
            $nodeId = $this->getMoveNodeId($result);
            $client = $this->getClient($nodeId);
            $this->clientConnect($client);
            //只处理一次moved,如果出错则不再处理
            $client->sendCommand($command);
            $result = $client->recv($timeout ?? $this->config->getTimeout());
        }

        if ($result->getStatus() === $result::STATUS_TIMEOUT) {
            //节点断线处理
            $this->clientDisconnect($client);
            throw new RedisClusterException($this->lastSocketError, $this->lastSocketErrno);
        }

        if ($result->getStatus() == $result::STATUS_ERR) {
            $this->setErrorMsg($result->getMsg());
            $this->setErrorType($result->getErrorType());
            throw new RedisClusterException($result->getMsg());
        }
        return $result;
    }

    public function sendCommand(array $com, ?ClusterClient $client = null): bool
    {
        if ($client === null) {
            $client = $this->getDefaultClient();
        } else {
            $this->setDefaultClient($client);
        }
        return $this->sendCommandByClient($com, $client);
    }

    public function rawCommand(array $command, ?ClusterClient $client = null)
    {
        $this->sendCommand($command, $client);
        $client = $this->getDefaultClient();
        return $this->recvByClient($client);
    }

    public function recv($timeout = null, ?ClusterClient $client = null): ?Response
    {
        if ($client === null) {
            $client = $this->getDefaultClient();
        } else {
            $this->setDefaultClient($client);
        }
        return $this->recvByClient($client, $timeout);
    }
    ###################### 发送接收tcp流数据 ######################

}
