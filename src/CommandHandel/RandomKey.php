<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class RandomKey extends AbstractCommandHandel
{
	public $commandName = 'RandomKey';


	public function handelCommandData(...$data)
	{
		

		$command = [CommandConst::RANDOMKEY];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
