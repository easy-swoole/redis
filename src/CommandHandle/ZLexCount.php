<?php
namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ZLexCount extends AbstractCommandHandle
{
	public $commandName = 'ZLexCount';


	public function handelCommandData(...$data)
	{
		$key=array_shift($data);
        $this->setClusterExecClientByKey($key);
        $min=array_shift($data);
		$max=array_shift($data);




		$command = [CommandConst::ZLEXCOUNT,$key,$min,$max];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
