<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class LSet extends AbstractCommandHandel
{
    public $commandName = 'LSet';


    public function handelCommandData(...$data)
    {
        $key = array_shift($data);
        $this->setClusterExecClientByKey($key);
        $index = array_shift($data);
        $value = array_shift($data);


        $value = $this->serialize($value);


        $command = [CommandConst::LSET, $key, $index, $value];
        $commandData = array_merge($command, $data);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        return true;
    }
}
