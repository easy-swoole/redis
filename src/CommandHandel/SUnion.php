<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class SUnion extends AbstractCommandHandel
{
    public $commandName = 'SUnion';


    public function handelCommandData(...$data)
    {
        $key1 = array_shift($data);

        $command = [CommandConst::SUNION, $key1];
        $commandData = array_merge($command, $data);
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
