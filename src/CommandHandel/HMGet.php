<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class HMGet extends AbstractCommandHandel
{
    public $commandName = 'HMGet';


    public function handelCommandData(...$data)
    {
        $key = array_shift($data);
        $this->setClusterExecClientByKey($key);
        $hashKeys = array_shift($data);

        $command = [CommandConst::HMGET, $key];
        $commandData = array_merge($command, $hashKeys);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        $data = $recv->getData();
        foreach ($data as $key => $value) {
            $data[$key] = $this->unSerialize($value);
        }

        return $data;
    }
}
