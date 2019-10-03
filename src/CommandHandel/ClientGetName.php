<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ClientGetName extends AbstractCommandHandel
{
	public $commandName = 'ClientGetName';


	public function getCommand(...$data)
	{
		

		$command = [CommandConst::CLIENTGETNAME];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
