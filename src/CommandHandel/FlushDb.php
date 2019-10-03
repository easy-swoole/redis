<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class FlushDb extends AbstractCommandHandel
{
	public $commandName = 'FlushDb';


	public function getCommand(...$data)
	{
		

		$command = [CommandConst::FLUSHDB];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return true;
	}
}
