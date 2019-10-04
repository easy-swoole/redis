<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ZUnionStore extends AbstractCommandHandel
{
	public $commandName = 'ZUnionStore';


	public function handelCommandData(...$data)
	{
		$destination=array_shift($data);
		$keyNum=array_shift($data);
		$key=array_shift($data);


		$command = [CommandConst::ZUNIONSTORE,$destination,$keyNum,$key];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
