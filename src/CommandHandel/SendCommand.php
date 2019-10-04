<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class SendCommand extends AbstractCommandHandel
{
	public static $commandName = 'SendCommand';


	public static function handelCommandData(...$data)
	{
		$com=array_shift($data);


		$command = [CommandConst::SENDCOMMAND,$com];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public static function handelRecv(Redis $redis, Response $recv)
	{
	}
}
