<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class LTrim extends AbstractCommandHandel
{
	public $commandName = 'LTrim';


	public function getCommand(...$data)
	{
		$key=array_shift($data);
		$start=array_shift($data);
		$stop=array_shift($data);


		        

		$command = [CommandConst::LTRIM,$key,$start,$stop];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return true;
	}
}