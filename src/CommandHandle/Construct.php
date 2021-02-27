<?php
namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Construct extends AbstractCommandHandle
{
	public static $commandName = 'Construct';


	public static function handelCommandData(...$data)
	{
		$config=array_shift($data);


		$command = [CommandConst::CONSTRUCT,$config];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public static function handelRecv(Redis $redis, Response $recv)
	{
		$this->config = $config;
	}
}
