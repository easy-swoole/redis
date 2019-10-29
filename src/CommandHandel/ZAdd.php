<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ZAdd extends AbstractCommandHandel
{
    public $commandName = 'ZAdd';


    public function handelCommandData(...$data)
    {
        $key = array_shift($data);
        $this->setClusterExecClientByKey($key);
        $score1 = array_shift($data);
        $member1 = array_shift($data);

        $member1 = $this->serialize($member1);
        $members = [];
        foreach ($data as $k => $va) {
            if ($k % 2 != 0) {
                $members[$k] = $this->serialize($va);
            }else{
                $members[$k] = $va;
            }
        }

        $command = [CommandConst::ZADD, $key, $score1, $member1];
        $commandData = array_merge($command, $members);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}
