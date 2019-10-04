<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class CommandCount extends AbstractCommandHandel
{
	public $commandName = 'CommandCount';


	public function handelCommandData(...$data)
	{
		

		$command = [CommandConst::COMMAND,'COUNT'];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
