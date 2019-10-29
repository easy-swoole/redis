<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class SMove extends AbstractCommandHandel
{
	public $commandName = 'SMove';


	public function handelCommandData(...$data)
	{
		$source=array_shift($data);
		$destination=array_shift($data);
		$member=array_shift($data);


		$member = $this->serialize($member);
		        

		$command = [CommandConst::SMOVE,$source,$destination,$member];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
