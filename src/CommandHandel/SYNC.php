<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class SYNC extends AbstractCommandHandel
{
	public $commandName = 'SYNC';


	public function getCommand(...$data)
	{
		

		$command = [CommandConst::SYNC];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
