<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class BgReWriteAof extends AbstractCommandHandel
{
	public $commandName = 'BgReWriteAof';


	public function getCommand(...$data)
	{
		

		$command = [CommandConst::BGREWRITEAOF];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
