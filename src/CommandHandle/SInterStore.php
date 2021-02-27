<?php
namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class SInterStore extends AbstractCommandHandle
{
	public $commandName = 'SInterStore';


	public function handelCommandData(...$data)
	{
		$destination=array_shift($data);

		$command = [CommandConst::SINTERSTORE,$destination,];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
