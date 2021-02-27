<?php

namespace EasySwoole\Redis\CommandHandle\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandle\AbstractCommandHandle;
use EasySwoole\Redis\Response;

class ClusterSetConfigEpoch extends AbstractCommandHandle
{
    public $commandName = 'ClusterSetConfigEpoch';

    public function handelCommandData(...$data)
    {
        $configEpoch = array_shift($data);
        $command = [CommandConst::CLUSTER, 'SET-CONFIG-EPOCH',$configEpoch];
        $commandData = array_merge($command,$data);
        return $commandData;
    }


    public function handelRecv(Response $recv):bool
    {
        return $recv->getData();
    }
}
