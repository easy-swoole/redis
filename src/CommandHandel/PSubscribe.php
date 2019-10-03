<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class PSubscribe extends AbstractCommandHandel
{
	public $commandName = 'PSubscribe';


	public function getCommand(...$data)
	{
		$callback=array_shift($data);
		$pattern=array_shift($data);
		$patterns=array_shift($data);


		        $command = array_merge([Command::PSUBSCRIBE, $pattern], $patterns);

		$command = [CommandConst::PSUBSCRIBE,$callback,$pattern,$patterns];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		$this->subscribeStop = false;
		        while ($this->subscribeStop == false) {
		            $recv = $this->recv(-1);
		            if ($recv === null) {
		                return false;
		            }
		            if ($recv->getData()[0] == 'pmessage') {
		                call_user_func($callback, $this, $recv->getData()[2], $recv->getData()[3]);
		            }
		        }
	}
}
