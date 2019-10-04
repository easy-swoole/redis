<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ZRem extends AbstractCommandHandel
{
    public $commandName = 'ZRem';


    public function getCommand(...$data)
    {
        $key = array_shift($data);
        $member = array_shift($data);

        $members = [];
        $member = $this->serialize($member);
        foreach ($data as $k => $va) {
            $members[$k] = $this->serialize($va);
        }

        $command = [CommandConst::ZREM, $key, $member];
        $commandData = array_merge($command, $members);
        return $commandData;
    }


    public function getData(Response $recv)
    {
        return $recv->getData();
    }
}
