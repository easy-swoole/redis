<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class SMembers extends AbstractCommandHandel
{
	public $commandName = 'SMembers';


	public function getCommand(...$data)
	{
		$key=array_shift($data);


		        

		$command = [CommandConst::SMEMBERS,$key];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		$data = $recv->getData();
		        foreach ($data as $key => $value) {
		            $data[$key] = $this->unSerialize($value);
		        }
		        return $data;
	}
}
