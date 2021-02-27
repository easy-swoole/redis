<?php
namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Decr extends AbstractCommandHandle
{
	public $commandName = 'Decr';


	public function handelCommandData(...$data)
	{
		$key=array_shift($data);




		$command = [CommandConst::DECR,$key];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
