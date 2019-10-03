<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ZRem extends AbstractCommandHandel
{
	public $commandName = 'ZRem';


	public function getCommand(...$data)
	{
		$key=array_shift($data);
		$member=array_shift($data);
		$members=array_shift($data);


		        $member = $this->serialize($member);
		        foreach ($members as $k => $va) {
		            $members[$k] = $this->serialize($va);
		        }
		        $command = array_merge([Command::ZREM, $key, $member], $members);

		$command = [CommandConst::ZREM,$key,$member,$members];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
