<?php

namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class SlowLog extends AbstractCommandHandle
{
    public $commandName = 'SlowLog';


    public function handelCommandData(...$data)
    {
        $subCommand = array_shift($data);

        $command = [CommandConst::SLOWLOG, $subCommand];
        $commandData = array_merge($command, $data);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}
