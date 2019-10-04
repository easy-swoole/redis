<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class SUnIonStore extends AbstractCommandHandel
{
	public $commandName = 'SUnIonStore';


	public function handelCommandData(...$data)
	{
		$destination=array_shift($data);
		$key1=array_shift($data);

		$command = [CommandConst::SUNIONSTORE,$destination,$key1];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
