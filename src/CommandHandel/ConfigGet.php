<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ConfigGet extends AbstractCommandHandel
{
	public $commandName = 'ConfigGet';


	public function getCommand(...$data)
	{
		$parameter=array_shift($data);


		        

		$command = [CommandConst::CONFIGGET,$parameter];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
