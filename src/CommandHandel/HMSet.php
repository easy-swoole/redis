<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class HMSet extends AbstractCommandHandel
{
    public $commandName = 'HMSet';


    public function handelCommandData(...$data)
    {
        $key = array_shift($data);
        $this->setClusterExecClientByKey($key);
        $data = array_shift($data);

        $kvData = [];
        foreach ($data as $k => $value) {
            $kvData[] = $k;
            $kvData[] = $this->serialize($value);
        }
        $command = [CommandConst::HMSET, $key];
        $commandData = array_merge($command, $kvData);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        return true;
    }
}
