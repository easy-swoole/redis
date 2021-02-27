<?php

namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Response;

class Get extends AbstractCommandHandle
{
    public $commandName = 'Get';
    public function handelCommandData(...$data)
    {
        $key = array_shift($data);
        $this->setClusterExecClientByKey($key);
        $command = [CommandConst::GET, $key];
        $commandData = $command;
        return $commandData;
    }

    public function handelRecv(Response $recv)
    {
        $data = $recv->getData();
        return $this->unSerialize($data);
    }
}
