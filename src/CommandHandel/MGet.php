<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class MGet extends AbstractCommandHandel
{
    public $commandName = 'MGet';


    public function handelCommandData(...$data)
    {
        $keys = array_shift($data);
        if (is_array($keys)) {
            $command = [CommandConst::MGET];
            $commandData = array_merge($command, $keys);
        } else {
            $command = [CommandConst::MGET, $keys];
            $commandData = $command;
        }
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
