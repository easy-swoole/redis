<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class GetSet extends AbstractCommandHandel
{
	public $commandName = 'GetSet';


	public function getCommand(...$data)
	{
		$key=array_shift($data);
		$value=array_shift($data);


		        $value = $this->serialize($value);
		        

		$command = [CommandConst::GETSET,$key,$value];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		$data = $recv->getData();
		        return $this->unSerialize($data);
	}
}
