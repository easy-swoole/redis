<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Publish extends AbstractCommandHandel
{
	public $commandName = 'Publish';


	public function handelCommandData(...$data)
	{
		$channel=array_shift($data);
		$message=array_shift($data);


		        

		$command = [CommandConst::PUBLISH,$channel,$message];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
