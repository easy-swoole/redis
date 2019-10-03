<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Monitor extends AbstractCommandHandel
{
	public $commandName = 'Monitor';


	public function getCommand(...$data)
	{
		$callback=array_shift($data);


		        

		$command = [CommandConst::MONITOR,$callback];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		$this->monitorStop = false;
		        while ($this->monitorStop == false) {
		            $recv = $this->recv(-1);
		            if ($recv === null) {
		                return false;
		            }
		            call_user_func($callback, $this, $recv->getData());
		        }
	}
}
