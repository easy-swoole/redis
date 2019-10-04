<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ConfigResetStat extends AbstractCommandHandel
{
	public $commandName = 'ConfigResetStat';


	public function getCommand(...$data)
	{
		

		$command = [CommandConst::CONFIGRESETSTAT];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return true;
	}
}