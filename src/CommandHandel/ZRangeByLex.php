<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ZRangeByLex extends AbstractCommandHandel
{
	public $commandName = 'ZRangeByLex';


	public function handelCommandData(...$data)
	{
		$key=array_shift($data);
        $this->setClusterExecClientByKey($key);
        $min=array_shift($data);
		$max=array_shift($data);

		$command = [CommandConst::ZRANGEBYLEX,$key,$min,$max];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		$data = $recv->getData();
		        foreach ($data as $key => $va) {
		            $data[$key] = $this->unSerialize($va);
		        }

		        return $data;
	}
}
