<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class CommandGetKeys extends AbstractCommandHandel
{
	public $commandName = 'CommandGetKeys';


	public function handelCommandData(...$data)
	{


		$command = [CommandConst::COMMAND,'GETKEYS'];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
