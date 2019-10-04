<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class GetErrorMsg extends AbstractCommandHandel
{
	public static $commandName = 'GetErrorMsg';


	public static function handelCommandData(...$data)
	{
		$command = [CommandConst::GETERRORMSG];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public static function handelRecv(Redis $redis, Response $recv)
	{
	}
}
