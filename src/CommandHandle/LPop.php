<?php

namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class LPop extends AbstractCommandHandle
{
    public $commandName = 'LPop';


    public function handelCommandData(...$data)
    {
        $key = array_shift($data);
        $this->setClusterExecClientByKey($key);

        $command = [CommandConst::LPOP, $key];
        $commandData = array_merge($command, $data);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        $data = $recv->getData();
        return $this->unSerialize($data);
    }
}
