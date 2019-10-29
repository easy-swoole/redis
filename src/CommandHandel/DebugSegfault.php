<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class DebugSegfault extends AbstractCommandHandel
{
	public $commandName = 'DebugSegfault';


	public function handelCommandData(...$data)
	{

		$command = [CommandConst::DEBUGSEGFAULT];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
