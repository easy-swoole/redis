<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ClientGetName extends AbstractCommandHandel
{
	public $commandName = 'ClientGetName';


	public function handelCommandData(...$data)
	{
		$command = [CommandConst::CLIENT,'GETNAME'];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
