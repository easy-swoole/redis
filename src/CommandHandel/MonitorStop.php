<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class MonitorStop extends AbstractCommandHandel
{
	public static $commandName = 'MonitorStop';


	public static function getCommand(...$data)
	{
		$command = [CommandConst::MONITORSTOP];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public static function getData(Redis $redis, Response $recv)
	{
	}
}
