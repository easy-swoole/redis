<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class BRPopLPush extends AbstractCommandHandel
{
	public $commandName = 'BRPopLPush';


	public function handelCommandData(...$data)
	{
		$source=array_shift($data);
		$destination=array_shift($data);
		$timeout=array_shift($data);

		$command = [CommandConst::BRPOPLPUSH,$source,$destination,$timeout];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		$data = $recv->getData();
		return $this->unSerialize($data);
	}
}
