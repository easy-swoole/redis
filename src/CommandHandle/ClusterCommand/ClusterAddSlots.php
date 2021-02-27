<?php

namespace EasySwoole\Redis\CommandHandle\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandle\AbstractCommandHandle;
use EasySwoole\Redis\Response;

class ClusterAddSlots extends AbstractCommandHandle
{
    public $commandName = 'ClusterAddSlots';

    public function handelCommandData(...$data)
    {
        $slot = array_shift($data);
        if (is_array($slot)) {
            $command = [CommandConst::CLUSTER, 'ADDSLOTS'];
            $commandData = array_merge($command, $slot);
        } else {
            $command = [CommandConst::CLUSTER, 'ADDSLOTS', $slot];
            $commandData = $command;
        }

        return $commandData;
    }


    public function handelRecv(Response $recv): bool
    {
        return true;
    }
}
