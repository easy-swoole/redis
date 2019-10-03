<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class SPop extends AbstractCommandHandel
{
	public $commandName = 'SPop';


	public function getCommand(...$data)
	{
		$key=array_shift($data);


		        

		$command = [CommandConst::SPOP,$key];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		$data = $recv->getData();
		        $data = $this->unSerialize($data);
		        return $data;
	}
}
