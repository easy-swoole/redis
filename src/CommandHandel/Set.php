<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Set extends AbstractCommandHandel
{
    public $commandName = 'Set';


    public function getCommand(...$data)
    {
        $key = array_shift($data);
        $val = array_shift($data);
        $expireTime = array_shift($data);
        $val = $this->serialize($val);
        $command = [CommandConst::SET, $key, $val];
        if ($expireTime != null && $expireTime > 0) {
            $command[] = 'EX ' . $expireTime;
        }
        $commandData = array_merge($command, $data);
        return $commandData;
    }


    public function getData(Response $recv)
    {
        return true;
    }
}
