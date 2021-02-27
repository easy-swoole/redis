<?php
namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class MSet extends AbstractCommandHandle
{
	public $commandName = 'MSet';


	public function handelCommandData(...$data)
	{
		$data=array_shift($data);
        $kvData = [];
        foreach ($data as $k => $value) {
           $kvData[] = $k;
           $kvData[] = $this->serialize($value);
        }
        $command = [CommandConst::MSET];
        $commandData = array_merge($command,$kvData);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return true;
	}
}
