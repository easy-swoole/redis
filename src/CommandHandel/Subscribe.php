<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Subscribe extends AbstractCommandHandel
{
	public $commandName = 'Subscribe';


	public function getCommand(...$data)
	{
		$callback=array_shift($data);
		$channel=array_shift($data);
		$channels=array_shift($data);


		        $command = array_merge([Command::SUBSCRIBE, $channel], $channels);

		$command = [CommandConst::SUBSCRIBE,$callback,$channel,$channels];
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
		            if ($recv->getData()[0] == 'message') {
		                call_user_func($callback, $this, $recv->getData()[1], $recv->getData()[2]);
		            }
		        }
	}
}
