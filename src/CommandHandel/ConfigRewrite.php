<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ConfigRewrite extends AbstractCommandHandel
{
	public $commandName = 'ConfigRewrite';


	public function getCommand(...$data)
	{
		

		$command = [CommandConst::CONFIGREWRITE];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return true;
	}
}
