<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ConfigGet extends AbstractCommandHandel
{
	public $commandName = 'ConfigGet';


	public function handelCommandData(...$data)
	{
		$parameter=array_shift($data);

		$command = [CommandConst::CONFIG,'GET',$parameter];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
