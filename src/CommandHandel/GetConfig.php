<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class GetConfig extends AbstractCommandHandel
{
	public static $commandName = 'GetConfig';


	public static function getCommand(...$data)
	{
		$command = [CommandConst::GETCONFIG];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public static function getData(Redis $redis, Response $recv)
	{
	}
}
