<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class BLPop extends AbstractCommandHandel
{
	public $commandName = 'BLPop';


	public function handelCommandData(...$data)
	{
		$keys=array_shift($data);
		$timeout=array_shift($data);
		if (is_string($keys)){
		    $keys = [$keys];
        }

		$command = array_merge([CommandConst::BLPOP],$keys);
        $command[] = $timeout;
		$commandData = $command;
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
	    $data = $recv->getData();

	    if (is_array($data)){
            return [$data[0] => $this->unSerialize($data[1])];
        }else{
	        return $data;
        }
	}
}
