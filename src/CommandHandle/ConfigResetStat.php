<?php
namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ConfigResetStat extends AbstractCommandHandle
{
	public $commandName = 'ConfigResetStat';


	public function handelCommandData(...$data)
	{


		$command = [CommandConst::CONFIG,'RESETSTAT'];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return true;
	}
}
