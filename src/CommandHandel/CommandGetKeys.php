<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class CommandGetKeys extends AbstractCommandHandel
{
	public $commandName = 'CommandGetKeys';


	public function getCommand(...$data)
	{


		$command = [CommandConst::COMMAND,'GETKEYS'];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
