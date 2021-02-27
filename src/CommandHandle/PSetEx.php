<?php

namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class PSetEx extends AbstractCommandHandle
{
    public $commandName = 'PSetEx';


    public function handelCommandData(...$data)
    {
        $key = array_shift($data);
        $this->setClusterExecClientByKey($key);
        $expireTime = array_shift($data);
        $value = array_shift($data);


        $value = $this->serialize($value);


        $command = [CommandConst::PSETEX, $key, $expireTime, $value];
        $commandData = array_merge($command, $data);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        return true;
    }
}
