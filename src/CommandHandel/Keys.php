<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Keys extends AbstractCommandHandel
{
	public $commandName = 'Keys';


	public function handelCommandData(...$data)
	{
		$pattern=array_shift($data);


		$command = [CommandConst::KEYS,$pattern];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
