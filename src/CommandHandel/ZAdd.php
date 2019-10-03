<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ZAdd extends AbstractCommandHandel
{
	public $commandName = 'ZAdd';


	public function getCommand(...$data)
	{
		$key=array_shift($data);
		$score1=array_shift($data);
		$member1=array_shift($data);
		$data=array_shift($data);


		        $member1 = $this->serialize($member1);
		        foreach ($data as $k => $va) {
		            if ($k % 2 != 0) {
		                $data[$k] = $this->serialize($va);
		            }
		        }

		        $command = array_merge([Command::ZADD, $key, $score1, $member1], $data);

		$command = [CommandConst::ZADD,$key,$score1,$member1,$data];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
