<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Select extends AbstractCommandHandel
{
	public $commandName = 'Select';


	public function handelCommandData(...$data)
	{
		$db=array_shift($data);

		$command = [CommandConst::SELECT,$db];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return true;
	}
}
