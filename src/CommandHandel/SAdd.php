<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class SAdd extends AbstractCommandHandel
{
    public $commandName = 'SAdd';


    public function handelCommandData(...$data)
    {
        $key = array_shift($data);
        $this->setClusterExecClientByKey($key);
        $data = array_shift($data);


        foreach ($data as $k => $va) {
            $data[$k] = $this->serialize($va);
        }

        $command = [CommandConst::SADD, $key];
        $commandData = array_merge($command, $data);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}
