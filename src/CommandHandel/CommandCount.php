<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class CommandCount extends AbstractCommandHandel
{
	public $commandName = 'CommandCount';


	public function getCommand(...$data)
	{
		

		$command = [CommandConst::COMMANDCOUNT];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
