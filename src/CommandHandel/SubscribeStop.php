<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class SubscribeStop extends AbstractCommandHandel
{
	public static $commandName = 'SubscribeStop';


	public static function handelCommandData(...$data)
	{
		$command = [CommandConst::SUBSCRIBESTOP];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public static function handelRecv(Redis $redis, Response $recv)
	{
		$this->subscribeStop = true;
	}
}
