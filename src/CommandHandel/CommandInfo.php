<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class CommandInfo extends AbstractCommandHandel
{
	public $commandName = 'CommandInfo';


	public function getCommand(...$data)
	{
		$commandName=array_shift($data);
		$commandNames=array_shift($data);


		        $command = array_merge([Command::COMMAND, 'INFO', $commandName,], $commandNames);

		$command = [CommandConst::COMMANDINFO,$commandName,$commandNames];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return $recv->getData();
	}
}
