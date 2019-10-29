<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ConfigSet extends AbstractCommandHandel
{
	public $commandName = 'ConfigSet';


	public function handelCommandData(...$data)
	{
		$parameter=array_shift($data);
		$value=array_shift($data);

		$command = [CommandConst::CONFIG,'SET',$parameter,$value];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return true;
	}
}
