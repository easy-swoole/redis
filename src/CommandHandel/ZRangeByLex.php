<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ZRangeByLex extends AbstractCommandHandel
{
	public $commandName = 'ZRangeByLex';


	public function getCommand(...$data)
	{
		$key=array_shift($data);
		$min=array_shift($data);
		$max=array_shift($data);
		$data=array_shift($data);


		        $command = array_merge([Command::ZRANGEBYLEX, $key, $min, $max], $data);

		$command = [CommandConst::ZRANGEBYLEX,$key,$min,$max,$data];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		$data = $recv->getData();
		        foreach ($data as $key => $va) {
		            $data[$key] = $this->unSerialize($va);
		        }

		        return $data;
	}
}
