<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class PSetEx extends AbstractCommandHandel
{
	public $commandName = 'PSetEx';


	public function getCommand(...$data)
	{
		$key=array_shift($data);
		$expireTime=array_shift($data);
		$value=array_shift($data);


		        $value = $this->serialize($value);
		        

		$command = [CommandConst::PSETEX,$key,$expireTime,$value];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return true;
	}
}
