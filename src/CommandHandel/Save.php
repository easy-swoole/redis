<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Save extends AbstractCommandHandel
{
	public $commandName = 'Save';


	public function getCommand(...$data)
	{
		

		$command = [CommandConst::SAVE];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return true;
	}
}
