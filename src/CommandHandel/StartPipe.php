<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\Pipe;
use EasySwoole\Redis\Response;

class StartPipe extends AbstractCommandHandel
{
	public $commandName = 'StartPipe';

	public function handelCommandData(...$data)
	{
		return true;
	}


	public function handelRecv(Response $recv)
	{
	    $this->redis->setPipe(new Pipe());
	    $this->redis->getPipe()->setIsStartPipe(true);
		return true;
	}
}
