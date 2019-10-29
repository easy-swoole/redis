<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ZRemRangeByRank extends AbstractCommandHandel
{
	public $commandName = 'ZRemRangeByRank';


	public function handelCommandData(...$data)
	{
		$key=array_shift($data);
        $this->setClusterExecClientByKey($key);
        $start=array_shift($data);
		$stop=array_shift($data);


		        

		$command = [CommandConst::ZREMRANGEBYRANK,$key,$start,$stop];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
