<?php
namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ClientGetName extends AbstractCommandHandle
{
	public $commandName = 'ClientGetName';


	public function handelCommandData(...$data)
	{
		$command = [CommandConst::CLIENT,'GETNAME'];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
