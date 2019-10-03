<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class PUnSubscribe extends AbstractCommandHandel
{
	public $commandName = 'PUnSubscribe';


	public function getCommand(...$data)
	{
		$pattern=array_shift($data);
		$patterns=array_shift($data);


		        $command = array_merge([Command::PUNSUBSCRIBE, $pattern], $patterns);

		$command = [CommandConst::PUNSUBSCRIBE,$pattern,$patterns];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
