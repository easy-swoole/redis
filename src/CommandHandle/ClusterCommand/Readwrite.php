<?php

namespace EasySwoole\Redis\CommandHandle\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandle\AbstractCommandHandle;
use EasySwoole\Redis\Response;

class Readwrite extends AbstractCommandHandle
{
    public $commandName = 'Readwrite';


    public function handelCommandData(...$data)
    {
        $command = [CommandConst::READWRITE];
        $commandData = array_merge($command);
        return $commandData;
    }


    public function handelRecv(Response $recv):bool
    {
        return true;
    }
}
