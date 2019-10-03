<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Recv extends AbstractCommandHandel
{
	public static $commandName = 'Recv';


	public static function getCommand(...$data)
	{
		$timeout=array_shift($data);


		$command = [CommandConst::RECV,$timeout];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public static function getData(Redis $redis, Response $recv)
	{
	}
}
