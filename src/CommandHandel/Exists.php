<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Exists extends AbstractCommandHandel
{
	public $commandName = 'Exists';


	public function handelCommandData(...$data)
	{
		$key=array_shift($data);

        $this->setClusterExecClientByKey($key);

		$command = [CommandConst::EXISTS,$key];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
