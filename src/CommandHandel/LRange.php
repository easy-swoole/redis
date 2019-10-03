<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class LRange extends AbstractCommandHandel
{
    public $commandName = 'LRange';


    public function getCommand(...$data)
    {
        $key = array_shift($data);
        $start = array_shift($data);
        $stop = array_shift($data);


        $command = [CommandConst::LRANGE, $key, $start, $stop];
        $commandData = array_merge($command, $data);
        return $commandData;
    }


    public function getData(Response $recv)
    {
        $data = $recv->getData();
        foreach ($data as $key => $va) {
            $data[$key] = $this->unSerialize($va);
        }
        return $data;
    }
}
