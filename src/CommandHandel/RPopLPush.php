<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class RPopLPush extends AbstractCommandHandel
{
	public $commandName = 'RPopLPush';


	public function handelCommandData(...$data)
	{
		$source=array_shift($data);
		$destination=array_shift($data);

		$command = [CommandConst::RPOPLPUSH,$source,$destination];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		$data = $recv->getData();
		        return $this->unSerialize($data);
	}
}
