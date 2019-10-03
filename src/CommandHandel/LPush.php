<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class LPush extends AbstractCommandHandel
{
    public $commandName = 'LPush';


    public function getCommand(...$data)
    {
        $key = array_shift($data);
        foreach ($data as $k=>$value){
            $data[$k] = $this->serialize($value);
        }

        $command = [CommandConst::LPUSH, $key];
        $commandData = array_merge($command, $data);
        return $commandData;
    }


    public function getData(Response $recv)
    {
        return $recv->getData();
    }
}
