<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class HGet extends AbstractCommandHandel
{
	public $commandName = 'HGet';


	public function getCommand(...$data)
	{
		$key=array_shift($data);
		$field=array_shift($data);




		$command = [CommandConst::HGET,$key,$field];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		$data = $recv->getData();
		        return $this->unSerialize($data);
	}
}
