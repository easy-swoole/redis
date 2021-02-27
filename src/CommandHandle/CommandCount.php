<?php
namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class CommandCount extends AbstractCommandHandle
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
