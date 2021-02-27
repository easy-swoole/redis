<?php

namespace EasySwoole\Redis\CommandHandle\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandle\AbstractCommandHandle;
use EasySwoole\Redis\Response;

class ClusterForget extends AbstractCommandHandle
{
    public $commandName = 'ClusterForget';


    public function handelCommandData(...$data)
    {
        $nodeId = array_shift($data);
        $command = [CommandConst::CLUSTER, 'FORGET', $nodeId];
        $commandData = array_merge($command);
        return $commandData;
    }


    public function handelRecv(Response $recv):bool
    {
        return true;
    }
}
