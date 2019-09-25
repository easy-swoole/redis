<?php


namespace EasySwoole\Redis;


class ClusterClient
{
    /**
     * @var $clientList Client[]
     */
    protected $clientList;
    /**
     * @var $nodeList Client[]
     */
    protected $nodeList;
    protected $lastCommandLog=[];
    protected $timeout = 3.0;

    function __construct(array $serverList,float $timeout=3.0)
    {
        $this->timeout=3.0;
        foreach ($serverList as $server){
            $host = $server[0];
            $port = $server[1];
            $this->clientList[] = new Client($host,$port,$timeout);
        }
    }

    public function sendCommand(array $commandList)
    {
       $result = $this->getClient()->sendCommand($commandList);
       array_push($this->lastCommandLog,$commandList);
       return $result;
    }

    function recv(): ?Response
    {
        $result = $this->getClient()->recv();
        $command = array_shift($this->lastCommandLog);
        if ($result->getErrorType()=='MOVED'){
            $nodeId = $this->getMoveNodeId($result);
            $client= $this->getNodeClient($nodeId);
            $client->sendCommand($command);
            $result = $client->recv();
        }
        return $result;
    }

    protected function getClient():Client{
        $client = $this->clientList[0];
        $client->connect();
        return $client;
    }

    protected function getNodeClient($nodeId):Client{
        $client = $this->nodeList[$nodeId];
        $client->connect();
        return $client;
    }

    protected function getMoveNodeId(Response $response){
        $data = explode(' ',$response->getMsg());
        $nodeId = $data[1];
        if (!isset($this->nodeList[$nodeId])){
            $server = explode(':',$data[2]);
            $this->nodeList[$nodeId] = new Client($server[0],$server[1],$this->timeout);
        }
        return $nodeId;
    }
}