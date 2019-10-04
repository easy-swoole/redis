<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class DecrBy extends AbstractCommandHandel
{
	public $commandName = 'DecrBy';


	public function handelCommandData(...$data)
	{
		$key=array_shift($data);
		$value=array_shift($data);


		        

		$command = [CommandConst::DECRBY,$key,$value];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
