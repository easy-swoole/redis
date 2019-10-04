<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ClientSetName extends AbstractCommandHandel
{
	public $commandName = 'ClientSetName';


	public function getCommand(...$data)
	{
		$connectionName=array_shift($data);

		$command = [CommandConst::CLIENT,'SETNAME',$connectionName];
		$commandData = array_merge($command);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return true;
	}
}
