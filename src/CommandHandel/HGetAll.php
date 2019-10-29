<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class HGetAll extends AbstractCommandHandel
{
    public $commandName = 'HGetAll';


    public function handelCommandData(...$data)
    {
        $key = array_shift($data);
        $this->setClusterExecClientByKey($key);

        $command = [CommandConst::HGETALL, $key];
        $commandData = array_merge($command, $data);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        $result = [];
        $data = $recv->getData();
        $dataCount = count($data);
        for ($i = 0; $i < $dataCount / 2; $i++) {
            $result[$data[$i * 2]] = $this->unSerialize($data[$i * 2 + 1]);
        }
        return $result;
    }
}
