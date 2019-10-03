<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class MGet extends AbstractCommandHandel
{
	public $commandName = 'MGet';


	public function getCommand(...$data)
	{
		$keys=array_shift($data);

		$command = [CommandConst::MGET,$keys];
		$commandData = array_merge($command,$data);
//		var_dump($commandData);
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
