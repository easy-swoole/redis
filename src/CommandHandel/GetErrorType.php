<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class GetErrorType extends AbstractCommandHandel
{
	public static $commandName = 'GetErrorType';


	public static function handelCommandData(...$data)
	{
		$command = [CommandConst::GETERRORTYPE];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public static function handelRecv(Redis $redis, Response $recv)
	{
	}
}
