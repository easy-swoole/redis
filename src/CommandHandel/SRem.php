<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class SRem extends AbstractCommandHandel
{
	public $commandName = 'SRem';


	public function getCommand(...$data)
	{
		$key=array_shift($data);
		$member1=array_shift($data);
		$members=array_shift($data);


		        $member1 = $this->serialize($member1);
		        foreach ($members as $k => $va) {
		            $members[$k] = $this->serialize($va);
		        }
		        $command = array_merge([Command::SREM, $key, $member1], $members);

		$command = [CommandConst::SREM,$key,$member1,$members];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
