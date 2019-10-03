<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class RPop extends AbstractCommandHandel
{
	public $commandName = 'RPop';


	public function getCommand(...$data)
	{
		$key=array_shift($data);


		        

		$command = [CommandConst::RPOP,$key];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		$data = $recv->getData();
		        return $this->unSerialize($data);
	}
}
