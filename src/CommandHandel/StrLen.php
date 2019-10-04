<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class StrLen extends AbstractCommandHandel
{
	public $commandName = 'StrLen';


	public function getCommand(...$data)
	{
		$key=array_shift($data);


		        

		$command = [CommandConst::STRLEN,$key];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}