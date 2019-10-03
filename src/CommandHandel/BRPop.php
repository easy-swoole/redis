<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class BRPop extends AbstractCommandHandel
{
	public $commandName = 'BRPop';


	public function getCommand(...$data)
	{
		$key1=array_shift($data);

		$command = [CommandConst::BRPOP,$key1];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return [$recv->getData()[0] => $this->unSerialize($recv->getData()[1])];
	}
}
