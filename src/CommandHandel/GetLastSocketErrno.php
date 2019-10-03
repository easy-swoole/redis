<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class GetLastSocketErrno extends AbstractCommandHandel
{
	public static $commandName = 'GetLastSocketErrno';


	public static function getCommand(...$data)
	{
		$command = [CommandConst::GETLASTSOCKETERRNO];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public static function getData(Redis $redis, Response $recv)
	{
	}
}
