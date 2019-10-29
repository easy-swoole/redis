<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ZRemRangeByLex extends AbstractCommandHandel
{
	public $commandName = 'ZRemRangeByLex';


	public function handelCommandData(...$data)
	{
		$key=array_shift($data);
        $this->setClusterExecClientByKey($key);
        $min=array_shift($data);
		$max=array_shift($data);


		        

		$command = [CommandConst::ZREMRANGEBYLEX,$key,$min,$max];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
