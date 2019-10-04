<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class SRem extends AbstractCommandHandel
{
    public $commandName = 'SRem';


    public function getCommand(...$data)
    {
        $key = array_shift($data);
        $member1 = array_shift($data);
        $members = [];

        $member1 = $this->serialize($member1);
        foreach ($data as $k => $va) {
            $members[$k] = $this->serialize($va);
        }
        $command = [CommandConst::SREM, $key, $member1];
        $commandData = array_merge($command, $members);
        return $commandData;
    }


    public function getData(Response $recv)
    {
        return $recv->getData();
    }
}