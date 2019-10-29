<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class GeoDist extends AbstractCommandHandel
{
	public $commandName = 'GeoDist';


	public function handelCommandData(...$data)
	{
		$key=array_shift($data);
        $this->setClusterExecClientByKey($key);
        $location1=array_shift($data);
		$location2=array_shift($data);
		$unit=array_shift($data);

		$command = [CommandConst::GEODIST,$key,$location1,$location2,$unit];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
