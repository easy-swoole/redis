<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class SDiff extends AbstractCommandHandel
{
	public $commandName = 'SDiff';


	public function getCommand(...$data)
	{
		$key1=array_shift($data);
		$keys=array_shift($data);


		        $command = array_merge([Command::SDIFF, $key1], $keys);

		$command = [CommandConst::SDIFF,$key1,$keys];
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
