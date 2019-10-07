<?php


namespace EasySwoole\Redis;


use EasySwoole\Redis\Exception\RedisClusterException;

class ClusterClient2
{
    protected $timeout = 3.0;

    protected $tryTimes = 3;

    function __construct(array $serverList, float $timeout = 3.0)
    {
        $this->timeout = $timeout;
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
}