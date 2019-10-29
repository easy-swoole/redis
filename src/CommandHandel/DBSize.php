<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class DBSize extends AbstractCommandHandel
{
	public $commandName = 'DBSize';


	public function handelCommandData(...$data)
	{

		$command = [CommandConst::DBSIZE];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
