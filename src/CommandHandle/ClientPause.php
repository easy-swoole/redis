<?php
namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ClientPause extends AbstractCommandHandle
{
	public $commandName = 'ClientPause';


	public function handelCommandData(...$data)
	{
		$timeout=array_shift($data);

		$command = [CommandConst::CLIENT,'PAUSE',$timeout];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return true;
	}
}
