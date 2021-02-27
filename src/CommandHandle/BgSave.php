<?php
namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class BgSave extends AbstractCommandHandle
{
	public $commandName = 'BgSave';


	public function handelCommandData(...$data)
	{
		$command = [CommandConst::BGSAVE];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
