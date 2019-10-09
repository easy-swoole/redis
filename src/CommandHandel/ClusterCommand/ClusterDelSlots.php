<?php

namespace EasySwoole\Redis\CommandHandel\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandel\AbstractCommandHandel;
use EasySwoole\Redis\Response;

class ClusterDelSlots extends AbstractCommandHandel
{
    public $commandName = 'ClusterDelSlots';


    public function handelCommandData(...$data)
    {
        $slot = array_shift($data);
        if (is_array($slot)) {
            $command = [CommandConst::CLUSTER, 'DELSLOTS'];
            $commandData = array_merge($command, $slot);
        } else {
            $command = [CommandConst::CLUSTER, 'DELSLOTS', $slot];
            $commandData = $command;
        }
        return $commandData;
    }


    public function handelRecv(Response $recv): bool
    {
        return true;
    }
}
