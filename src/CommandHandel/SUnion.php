<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class SUnion extends AbstractCommandHandel
{
	public $commandName = 'SUnion';


	public function getCommand(...$data)
	{
		$key1=array_shift($data);
		$keys=array_shift($data);


		        $command = array_merge([Command::SUNION, $key1], $keys);

		$command = [CommandConst::SUNION,$key1,$keys];
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
