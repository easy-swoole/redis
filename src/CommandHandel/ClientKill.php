<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ClientKill extends AbstractCommandHandel
{
	public $commandName = 'ClientKill';


	public function getCommand(...$data)
	{
		$data=array_shift($data);




		$command = [CommandConst::CLIENTKILL,$data];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return true;
	}
}
