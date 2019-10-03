<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class IncrBy extends AbstractCommandHandel
{
	public $commandName = 'IncrBy';


	public function getCommand(...$data)
	{
		$key=array_shift($data);
		$value=array_shift($data);


		        

		$command = [CommandConst::INCRBY,$key,$value];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
