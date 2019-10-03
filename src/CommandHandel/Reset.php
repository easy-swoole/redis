<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Reset extends AbstractCommandHandel
{
	public static $commandName = 'Reset';


	public static function getCommand(...$data)
	{
		$command = [CommandConst::RESET];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public static function getData(Redis $redis, Response $recv)
	{
		$this->monitorStop = true;
		        $this->errorMsg = '';
		        $this->errorType = '';
		    }

		    public function auth($password): bool
		    {
	}
}
