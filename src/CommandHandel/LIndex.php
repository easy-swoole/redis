<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class LIndex extends AbstractCommandHandel
{
	public $commandName = 'LIndex';


	public function getCommand(...$data)
	{
		$key=array_shift($data);
		$index=array_shift($data);


		        

		$command = [CommandConst::LINDEX,$key,$index];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		$data = $recv->getData();
		        return $this->unSerialize($data);
	}
}
