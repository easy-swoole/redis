<?php

namespace EasySwoole\Redis\CommandHandle\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandle\AbstractCommandHandle;
use EasySwoole\Redis\Response;

class ClusterCountFailureReports extends AbstractCommandHandle
{
    public $commandName = 'ClusterCountFailureReports';

    public function handelCommandData(...$data)
    {
        $nodeId = array_shift($data);
        $command = [CommandConst::CLUSTER, 'COUNT-FAILURE-REPORTS',$nodeId];
        $commandData = array_merge($command,$data);
        return $commandData;
    }

    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}
