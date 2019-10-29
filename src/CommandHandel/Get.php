<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\RedisCluster;
use EasySwoole\Redis\Response;

class Get extends AbstractCommandHandel
{
    public $commandName = 'Get';
    public function handelCommandData(...$data)
    {
        $key = array_shift($data);
        $this->key = $key;
        $this->setClusterExecClientByKey($key);

        $command = [CommandConst::GET, $key];
        $commandData = array_merge($command, $data);
        return $commandData;
    }

    public function handelRecv(Response $recv)
    {
        $data = $recv->getData();
        return $this->unSerialize($data);
    }
}
