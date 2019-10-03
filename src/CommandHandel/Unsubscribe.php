<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Unsubscribe extends AbstractCommandHandel
{
	public $commandName = 'Unsubscribe';


	public function getCommand(...$data)
	{
		$channel=array_shift($data);
		$channels=array_shift($data);


		        $command = array_merge([Command::UNSUBSCRIBE, $channel], $channels);

		$command = [CommandConst::UNSUBSCRIBE,$channel,$channels];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
