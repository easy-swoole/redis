<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class PfMerge extends AbstractCommandHandel
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
