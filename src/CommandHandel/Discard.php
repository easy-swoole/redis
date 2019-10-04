<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Discard extends AbstractCommandHandel
{
	public $commandName = 'Discard';


	public function handelCommandData(...$data)
	{
		$command = [CommandConst::DISCARD];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
        $this->redis->getTransaction()->setCommandLog([]);
        $this->redis->getTransaction()->setIsTransaction(false);
		return true;
	}
}
