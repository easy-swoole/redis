<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class GeoAdd extends AbstractCommandHandel
{
	public $commandName = 'GeoAdd';


	public function handelCommandData(...$data)
	{
		$key=array_shift($data);
        $this->setClusterExecClientByKey($key);
        $data = array_shift($data);
        $command = [CommandConst::GEOADD,$key];
        foreach ($data as $locationData){
            $command[] = $locationData['longitude']??$locationData[0];
            $command[] = $locationData['latitude']??$locationData[1];
            $command[] = $locationData['name']??$locationData[2];
        }

		$commandData = array_merge($command);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
