<?php
namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class PfMerge extends AbstractCommandHandle
{
	public $commandName = 'PfMerge';


	public function handelCommandData(...$data)
	{
		$deStKey=array_shift($data);
		$sourceKeys=array_shift($data);

		$command = [CommandConst::PFMERGE,$deStKey];
		$commandData = array_merge($command,$sourceKeys);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return true;
	}
}
