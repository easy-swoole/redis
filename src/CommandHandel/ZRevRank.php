<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ZRevRank extends AbstractCommandHandel
{
	public $commandName = 'ZRevRank';


	public function getCommand(...$data)
	{
		$key=array_shift($data);
		$member=array_shift($data);


		        $member = $this->serialize($member);
		        

		$command = [CommandConst::ZREVRANK,$key,$member];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
