<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Disconnect extends AbstractCommandHandel
{
	public static $commandName = 'Disconnect';


	public static function getCommand(...$data)
	{
		$command = [CommandConst::DISCONNECT];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public static function getData(Redis $redis, Response $recv)
	{
	}
}
