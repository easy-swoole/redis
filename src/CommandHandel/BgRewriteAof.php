<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class BgRewriteAof extends AbstractCommandHandel
{
	public $commandName = 'BgReWriteAof';


	public function handelCommandData(...$data)
	{
		$command = [CommandConst::BGREWRITEAOF];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
