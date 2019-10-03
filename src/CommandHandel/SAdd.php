<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class SAdd extends AbstractCommandHandel
{
	public $commandName = 'SAdd';


	public function getCommand(...$data)
	{
		$key=array_shift($data);
		$data=array_shift($data);


		        foreach ($data as $k => $va) {
		            $data[$k] = $this->serialize($va);
		        }
		        $command = array_merge([Command::SADD, $key], $data);

		$command = [CommandConst::SADD,$key,$data];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
