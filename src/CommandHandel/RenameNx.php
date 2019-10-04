<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class RenameNx extends AbstractCommandHandel
{
	public $commandName = 'RenameNx';


	public function handelCommandData(...$data)
	{
		$key=array_shift($data);
		$new_key=array_shift($data);


		        

		$command = [CommandConst::RENAMENX,$key,$new_key];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function handelRecv(Response $recv)
	{
		return $recv->getData();
	}
}
