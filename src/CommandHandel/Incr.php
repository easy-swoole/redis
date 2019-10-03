<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Incr extends AbstractCommandHandel
{
	public $commandName = 'Incr';


	public function getCommand(...$data)
	{
		$key=array_shift($data);


		        

		$command = [CommandConst::INCR,$key];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
