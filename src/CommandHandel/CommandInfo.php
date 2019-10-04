<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class CommandInfo extends AbstractCommandHandel
{
	public $commandName = 'CommandInfo';


	public function handelCommandData(...$data)
	{
		$commandName=array_shift($data);

		$command = [CommandConst::COMMAND, 'INFO',$commandName];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
