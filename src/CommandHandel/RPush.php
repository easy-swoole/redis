<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class RPush extends AbstractCommandHandel
{
    public $commandName = 'RPush';


    public function getCommand(...$data)
    {
        $key = array_shift($data);

        foreach ($data as $k => $va) {
            $data[$k] = $this->serialize($va);
        }
        $command = [CommandConst::RPUSH, $key];
        $commandData = array_merge($command, $data);
        return $commandData;
    }


    public function getData(Response $recv)
    {
        return $recv->getData();
    }
}