<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class HIncrBy extends AbstractCommandHandel
{
	public $commandName = 'HIncrBy';


	public function getCommand(...$data)
	{
		$key=array_shift($data);
		$field=array_shift($data);
		$increment=array_shift($data);


		        

		$command = [CommandConst::HINCRBY,$key,$field,$increment];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
