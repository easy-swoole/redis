<?php
namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\RedisTransaction;
use EasySwoole\Redis\Response;

class Multi extends AbstractCommandHandle
{
	public $commandName = 'Multi';


	public function handelCommandData(...$data)
	{
		$command = [CommandConst::MULTI];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
	    $this->redis->setTransaction(new RedisTransaction());
	    $this->redis->getTransaction()->setIsTransaction(true);
		return true;
	}
}
