<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class SlowLog extends AbstractCommandHandel
{
	public $commandName = 'SlowLog';


	public function getCommand(...$data)
	{
		$subCommand=array_shift($data);
		$argument=array_shift($data);


		        $command = array_merge([Command::SLOWLOG, $subCommand,], $argument);

		$command = [CommandConst::SLOWLOG,$subCommand,$argument];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
