<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Rename extends AbstractCommandHandel
{
	public $commandName = 'Rename';


	public function handelCommandData(...$data)
	{
		$key=array_shift($data);
		$new_key=array_shift($data);


		        

		$command = [CommandConst::RENAME,$key,$new_key];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return true;
	}
}
