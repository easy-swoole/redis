<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Exec extends AbstractCommandHandel
{
	public $commandName = 'Exec';


	public function handelCommandData(...$data)
	{
		$command = [CommandConst::EXEC];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
	    $data = $recv->getData();
	    $commandLog = $this->redis->getTransaction()->getCommandLog();
        $this->redis->getTransaction()->setCommandLog([]);
        $this->redis->getTransaction()->setIsTransaction(false);
        if ($data===null){
            return $data;
        }
	    foreach ($data as $k=>$value){
	        $commandClassName = "\\EasySwoole\\Redis\\CommandHandel\\".array_shift($commandLog);
	        $commandClass = new $commandClassName($this->redis);
	        $response = new Response();
            $response->setData($value);
            $response->setStatus($response::STATUS_OK);
            $data[$k] = $commandClass->getData($response);
        }

		return $data;
	}
}
