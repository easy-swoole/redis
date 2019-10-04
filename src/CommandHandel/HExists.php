<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class HExists extends AbstractCommandHandel
{
	public $commandName = 'HExists';


	public function getCommand(...$data)
	{
		$key=array_shift($data);
		$field=array_shift($data);


		        

		$command = [CommandConst::HEXISTS,$key,$field];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}