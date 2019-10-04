<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class MSetNx extends AbstractCommandHandel
{
    public $commandName = 'MSetNx';


    public function handelCommandData(...$data)
    {
        $data = array_shift($data);
        $kvData = [];
        foreach ($data as $key => $value) {
            $kvData[] = $key;
            $kvData[] = $this->serialize($value);
        }
        $command = [CommandConst::MSETNX];
        $commandData = array_merge($command, $kvData);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}
