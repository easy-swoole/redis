<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class LTrim extends AbstractCommandHandel
{
	public $commandName = 'LTrim';


	public function handelCommandData(...$data)
	{
		$key=array_shift($data);
        $this->setClusterExecClientByKey($key);
        $start=array_shift($data);
		$stop=array_shift($data);


		        

		$command = [CommandConst::LTRIM,$key,$start,$stop];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return true;
	}
}
