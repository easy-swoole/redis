<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class SUnIomStore extends AbstractCommandHandel
{
	public $commandName = 'SUnIomStore';


	public function getCommand(...$data)
	{
		$destination=array_shift($data);
		$key1=array_shift($data);
		$keys=array_shift($data);


		        $command = array_merge([Command::SUNIONSTORE, $destination, $key1], $keys);

		$command = [CommandConst::SUNIOMSTORE,$destination,$key1,$keys];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
