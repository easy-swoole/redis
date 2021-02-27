<?php

namespace EasySwoole\Redis\CommandHandle\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandle\AbstractCommandHandle;
use EasySwoole\Redis\Response;

class ClusterSlots extends AbstractCommandHandle
{
    public $commandName = 'ClusterSlaves';


    public function handelCommandData(...$data)
    {
        $command = [CommandConst::CLUSTER, 'SLOTS'];
        $commandData = array_merge($command);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}
