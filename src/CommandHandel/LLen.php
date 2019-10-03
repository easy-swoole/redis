<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class LLen extends AbstractCommandHandel
{
	public $commandName = 'LLen';


	public function getCommand(...$data)
	{
		$key=array_shift($data);


		        

		$command = [CommandConst::LLEN,$key];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
