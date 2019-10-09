<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\RedisTransaction;
use EasySwoole\Redis\Response;

class StartPipe extends AbstractCommandHandel
{
	public $commandName = 'StartPipe';

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
