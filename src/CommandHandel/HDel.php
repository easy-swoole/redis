<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class HDel extends AbstractCommandHandel
{
	public $commandName = 'HDel';


	public function getCommand(...$data)
	{
		$key=array_shift($data);
		$field=array_shift($data);

		$command = [CommandConst::HDEL,$key,$field];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
