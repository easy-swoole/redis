<?php
namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class FlushAll extends AbstractCommandHandle
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
