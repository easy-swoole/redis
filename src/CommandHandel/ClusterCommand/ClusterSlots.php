<?php

namespace EasySwoole\Redis\CommandHandel\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandel\AbstractCommandHandel;
use EasySwoole\Redis\Response;

class ClusterSlots extends AbstractCommandHandel
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
