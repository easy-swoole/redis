<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class FlushAll extends AbstractCommandHandel
{
	public $commandName = 'FlushAll';


	public function handelCommandData(...$data)
	{
		$command = [CommandConst::FLUSHALL];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return true;
	}
}
