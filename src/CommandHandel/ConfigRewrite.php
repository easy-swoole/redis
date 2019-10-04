<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ConfigRewrite extends AbstractCommandHandel
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
