<?php
namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Connect extends AbstractCommandHandel
{
	public static $commandName = 'Connect';


	public static function handelCommandData(...$data)
	{
		$timeout=array_shift($data);


		$command = [CommandConst::CONNECT,$timeout];
		$commandData = array_merge($command,$data);
		return $commandData;
	}


	public static function handelRecv(Redis $redis, Response $recv)
	{
		}

		    ######################服务器连接方法######################

		    public function connect(float $timeout = null): bool
		    {
		        if ($this->isConnected) {
		            return true;
		        }
		        if ($timeout === null) {
		            $timeout = $this->config->getTimeout();
		        }
		        if ($this->client == null) {
		            $this->client = new Client($this->config->getHost(), $this->config->getPort());
		        }
		        $this->isConnected = $this->client->connect($timeout);
		        if ($this->isConnected && !empty($this->config->getAuth())) {
	}
}
