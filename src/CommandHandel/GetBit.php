<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class GetBit extends AbstractCommandHandel
{
	public $commandName = 'GetBit';


	public function getCommand(...$data)
	{
		$key=array_shift($data);
		$offset=array_shift($data);


		        

		$command = [CommandConst::GETBIT,$key,$offset];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
