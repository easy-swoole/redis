<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ClientPause extends AbstractCommandHandel
{
	public $commandName = 'ClientPause';


	public function getCommand(...$data)
	{
		$timeout=array_shift($data);


		        

		$command = [CommandConst::CLIENTPAUSE,$timeout];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return true;
	}
}