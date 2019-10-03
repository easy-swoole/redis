<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class MSet extends AbstractCommandHandel
{
	public $commandName = 'MSet';


	public function getCommand(...$data)
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


	public function getData(Response $recv)
	{
		return true;
	}
}
