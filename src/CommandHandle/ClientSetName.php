<?php
namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ClientSetName extends AbstractCommandHandle
{
	public $commandName = 'ClientSetName';


	public function handelCommandData(...$data)
	{
		$connectionName=array_shift($data);

		$command = [CommandConst::CLIENT,'SETNAME',$connectionName];
		$commandData = array_merge($command);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return true;
	}
}
