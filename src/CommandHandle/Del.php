<?php
namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Del extends AbstractCommandHandle
{
	public $commandName = 'Del';


	public function handelCommandData(...$data)
	{
		$key=array_shift($data);
		if (is_array($key)){
            $command = [CommandConst::DEL];
            $commandData = array_merge($command,$key);
        }else{
            $command = [CommandConst::DEL,$key];
            $commandData = array_merge($command,$data);
        }

		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
