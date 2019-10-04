<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ZScore extends AbstractCommandHandel
{
	public $commandName = 'ZScore';


	public function getCommand(...$data)
	{
		$key=array_shift($data);
		$member=array_shift($data);


		        $member = $this->serialize($member);
		        

		$command = [CommandConst::ZSCORE,$key,$member];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}