<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class HMGet extends AbstractCommandHandel
{
    public $commandName = 'HMGet';


    public function getCommand(...$data)
    {
        $key = array_shift($data);
        $field = array_shift($data);

        $command = [CommandConst::HMGET, $key, $field];
        $commandData = array_merge($command, $data);
        return $commandData;
    }


    public function getData(Response $recv)
    {
        $data = $recv->getData();
        foreach ($data as $key => $value) {
            $data[$key] = $this->unSerialize($value);
        }

        return $data;
    }
}
