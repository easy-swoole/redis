<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class PfMerge extends AbstractCommandHandel
{
	public $commandName = 'PfMerge';


	public function getCommand(...$data)
	{
		$deStKey=array_shift($data);
		$sourceKey=array_shift($data);
		$sourceKeys=array_shift($data);


		        $command = array_merge([Command::PFMERGE, $deStKey, $sourceKey], $sourceKeys);

		$command = [CommandConst::PFMERGE,$deStKey,$sourceKey,$sourceKeys];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public function getData(Response $recv)
	{
		return true;
	}
}
