<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class BLPop extends AbstractCommandHandel
{
	public $commandName = 'BLPop';


	public function getCommand(...$data)
	{
		$key1=array_shift($data);

		$command = [CommandConst::BLPOP,$key1];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
	    $data = $recv->getData();
		return [$data[0] => $this->unSerialize($data[1])];
	}
}
