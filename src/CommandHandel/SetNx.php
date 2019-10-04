<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class SetNx extends AbstractCommandHandel
{
	public $commandName = 'SetNx';


	public function handelCommandData(...$data)
	{
		$key=array_shift($data);
		$value=array_shift($data);


		        

		$command = [CommandConst::SETNX,$key,$value];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
