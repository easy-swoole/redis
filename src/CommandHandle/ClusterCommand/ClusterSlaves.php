<?php

namespace EasySwoole\Redis\CommandHandle\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandle\AbstractCommandHandle;
use EasySwoole\Redis\Response;

class ClusterSlaves extends AbstractCommandHandle
{
    public $commandName = 'ClusterSlaves';

    public function handelCommandData(...$data)
    {
        $nodeId  = array_shift($data);
        $command = [CommandConst::CLUSTER, 'SLAVES', $nodeId];
        $commandData = array_merge($command);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}
