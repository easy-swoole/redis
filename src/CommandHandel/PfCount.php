<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class PfCount extends AbstractCommandHandel
{
	public $commandName = 'PfCount';


	public function handelCommandData(...$data)
	{
		$key=array_shift($data);
        if (is_array($key)){
            $command = [CommandConst::PFCOUNT];
            $commandData = array_merge($command,$key);
        }else{
            $command = [CommandConst::PFCOUNT,$key];
            $commandData = $command;
        }
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
