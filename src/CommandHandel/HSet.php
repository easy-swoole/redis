<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class HSet extends AbstractCommandHandel
{
    public $commandName = 'HSet';


    public function handelCommandData(...$data)
    {
        $key = array_shift($data);
        $this->setClusterExecClientByKey($key);
        $field = array_shift($data);
        $value = array_shift($data);

        $value = $this->serialize($value);

        $command = [CommandConst::HSET, $key, $field, $value];
        $commandData = array_merge($command, $data);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}
