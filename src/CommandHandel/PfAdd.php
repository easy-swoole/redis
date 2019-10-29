<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class PfAdd extends AbstractCommandHandel
{
	public $commandName = 'PfAdd';


	public function handelCommandData(...$data)
	{
		$key=array_shift($data);
        $this->setClusterExecClientByKey($key);
        $elements=array_shift($data);

		$command = [CommandConst::PFADD,$key];
		$commandData = array_merge($command,$elements);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
