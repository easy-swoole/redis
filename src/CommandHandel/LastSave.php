<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class LastSave extends AbstractCommandHandel
{
	public $commandName = 'LastSave';


	public function handelCommandData(...$data)
	{

		$command = [CommandConst::LASTSAVE];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
