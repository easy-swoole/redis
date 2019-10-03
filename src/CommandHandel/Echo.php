<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Echo extends AbstractCommandHandel
{
	public static $commandName = 'Echo';


	public static function getCommand(...$data)
	{
		$str=array_shift($data);


		        

		$command = [CommandConst::ECHO,$str];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public static function getData(Redis $redis, Response $recv)
	{
		if ($recv === null) {
		            return false;
		        }
		        return $recv->getData();
	}
}
