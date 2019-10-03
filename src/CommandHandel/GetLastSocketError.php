<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class GetLastSocketError extends AbstractCommandHandel
{
	public static $commandName = 'GetLastSocketError';


	public static function getCommand(...$data)
	{
		$command = [CommandConst::GETLASTSOCKETERROR];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public static function getData(Redis $redis, Response $recv)
	{
	}
}
