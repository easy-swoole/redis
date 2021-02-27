<?php

namespace EasySwoole\Redis\CommandHandle\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandle\AbstractCommandHandle;
use EasySwoole\Redis\Response;

class ClusterReplicate extends AbstractCommandHandle
{
    public $commandName = 'ClusterReplicate';


    public function handelCommandData(...$data)
    {
        $nodeId = array_shift($data);
        $command = [CommandConst::CLUSTER, 'REPLICATE', $nodeId];
        $commandData = array_merge($command);
        return $commandData;
    }


    public function handelRecv(Response $recv):bool
    {
        return true;
    }
}
