<?php
namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Unsubscribe extends AbstractCommandHandle
{
	public $commandName = 'Unsubscribe';


	public function handelCommandData(...$data)
	{
		$channel=array_shift($data);


		$command = [CommandConst::UNSUBSCRIBE,$channel];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
