<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class PfCount extends AbstractCommandHandel
{
	public $commandName = 'PfCount';


	public function getCommand(...$data)
	{
		$key=array_shift($data);


		$command = [CommandConst::PFCOUNT,$key];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}