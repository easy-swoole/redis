<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class PubSub extends AbstractCommandHandel
{
	public $commandName = 'PubSub';


	public function getCommand(...$data)
	{
		$subCommand=array_shift($data);
		$arguments=array_shift($data);


		        $command = array_merge([Command::PUBSUB, $subCommand,], $arguments);

		$command = [CommandConst::PUBSUB,$subCommand,$arguments];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
