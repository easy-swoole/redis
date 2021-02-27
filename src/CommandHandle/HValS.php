<?php

namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class HValS extends AbstractCommandHandle
{
    public $commandName = 'HValS';


    public function handelCommandData(...$data)
    {
        $key = array_shift($data);
        $this->setClusterExecClientByKey($key);

        $command = [CommandConst::HVALS, $key];
        $commandData = array_merge($command);
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
