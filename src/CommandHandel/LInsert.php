<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class LInsert extends AbstractCommandHandel
{
    public $commandName = 'LInsert';


    public function getCommand(...$data)
    {
        $key = array_shift($data);
        $isBefore = array_shift($data);
        $pivot = array_shift($data);
        $value = array_shift($data);

        $value = $this->serialize($value);
        $pivot = $this->serialize($pivot);
        $command = [CommandConst::LINSERT, $key, $isBefore, $pivot, $value];
        $commandData = array_merge($command);
        return $commandData;
    }


    public function getData(Response $recv)
    {
        return $recv->getData();
    }
}
