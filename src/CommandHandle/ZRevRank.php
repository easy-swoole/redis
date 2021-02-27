<?php

namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ZRevRank extends AbstractCommandHandle
{
    public $commandName = 'ZRevRank';


    public function handelCommandData(...$data)
    {
        $key = array_shift($data);
        $this->setClusterExecClientByKey($key);
        $member = array_shift($data);


        $member = $this->serialize($member);


        $command = [CommandConst::ZREVRANK, $key, $member];
        $commandData = array_merge($command, $data);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}
