<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Expire extends AbstractCommandHandel
{
	public $commandName = 'Expire';


	public function getCommand(...$data)
	{
		$key=array_shift($data);
		$expireTime=array_shift($data);


		        

		$command = [CommandConst::EXPIRE,$key,$expireTime];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
