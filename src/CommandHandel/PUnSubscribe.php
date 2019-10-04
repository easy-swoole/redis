<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class PUnSubscribe extends AbstractCommandHandel
{
	public $commandName = 'PUnSubscribe';


	public function handelCommandData(...$data)
	{
		$pattern=array_shift($data);

		$command = [CommandConst::PUNSUBSCRIBE,$pattern];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
