<?php
namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class PubSub extends AbstractCommandHandle
{
	public $commandName = 'PubSub';


	public function handelCommandData(...$data)
	{
		$subCommand=array_shift($data);


		$command = [CommandConst::PUBSUB,$subCommand];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
