<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ZCard extends AbstractCommandHandel
{
	public $commandName = 'ZCard';


	public function getCommand(...$data)
	{
		$key=array_shift($data);


		        

		$command = [CommandConst::ZCARD,$key];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}