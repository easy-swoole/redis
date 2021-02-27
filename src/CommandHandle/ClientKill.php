<?php
namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ClientKill extends AbstractCommandHandle
{
	public $commandName = 'ClientKill';


	public function handelCommandData(...$data)
	{
		$data=array_shift($data);

		$command = [CommandConst::CLIENT,'KILL',$data];
		$commandData = array_merge($command);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return true;
	}
}
