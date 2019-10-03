<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class GeoPos extends AbstractCommandHandel
{
	public $commandName = 'GeoPos';


	public function getCommand(...$data)
	{
		$key=array_shift($data);
		$location1=array_shift($data);
		$locations=array_shift($data);


		        $command = array_merge([Command::GEOPOS, $key, $location1,], $locations);

		$command = [CommandConst::GEOPOS,$key,$location1,$locations];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
