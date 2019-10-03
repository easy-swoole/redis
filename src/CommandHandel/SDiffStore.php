<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class SDiffStore extends AbstractCommandHandel
{
	public $commandName = 'SDiffStore';


	public function getCommand(...$data)
	{
		$destination=array_shift($data);
		$keys=array_shift($data);


		        $command = array_merge([Command::SDIFFSTORE, $destination], $keys);

		$command = [CommandConst::SDIFFSTORE,$destination,$keys];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
