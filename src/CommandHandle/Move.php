<?php
namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Move extends AbstractCommandHandle
{
	public $commandName = 'Move';


	public function handelCommandData(...$data)
	{
		$key=array_shift($data);
		$db=array_shift($data);

		$command = [CommandConst::MOVE,$key,$db];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
        return $recv->getData();
	}
}
