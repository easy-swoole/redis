<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Keys extends AbstractCommandHandel
{
	public $commandName = 'Keys';


	public function getCommand(...$data)
	{
		$pattern=array_shift($data);


		        

		$command = [CommandConst::KEYS,$pattern];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
