<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class HValS extends AbstractCommandHandel
{
    public $commandName = 'HValS';


    public function getCommand(...$data)
    {
        $key = array_shift($data);

        $command = [CommandConst::HVALS, $key];
        $commandData = array_merge($command);
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
