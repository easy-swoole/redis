<?php
namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ConfigRewrite extends AbstractCommandHandle
{
	public $commandName = 'ConfigRewrite';


	public function handelCommandData(...$data)
	{
		$command = [CommandConst::CONFIG,'REWRITE'];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return true;
	}
}
