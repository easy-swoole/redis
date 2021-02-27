<?php
namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Unlink extends AbstractCommandHandle
{
	public $commandName = 'Unlink';


	public function handelCommandData(...$data)
	{
		$key=array_shift($data);
		if (is_array($key)){
            $command = [CommandConst::UNLINK];
            $commandData = array_merge($command,$key);
        }else{
            $command = [CommandConst::UNLINK,$key];
            $commandData = array_merge($command,$data);
        }

		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
