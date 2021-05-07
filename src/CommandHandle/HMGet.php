<?php

namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class HMGet extends AbstractCommandHandle
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
        $fieldData = array_slice($this->commandData,2);
        $data = $recv->getData();
        $array = [];
        foreach ($data as $key => $value) {
            $array[$fieldData[$key]] = $this->unSerialize($value);
        }

        return $array;
    }
}
