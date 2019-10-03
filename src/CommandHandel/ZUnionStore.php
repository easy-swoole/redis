<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ZUnionStore extends AbstractCommandHandel
{
	public $commandName = 'ZUnionStore';


	public function getCommand(...$data)
	{
		$destination=array_shift($data);
		$keyNum=array_shift($data);
		$key=array_shift($data);
		$data=array_shift($data);


		        $command = array_merge([Command::ZUNIONSTORE, $destination, $keyNum, $key], $data);

		$command = [CommandConst::ZUNIONSTORE,$destination,$keyNum,$key,$data];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
