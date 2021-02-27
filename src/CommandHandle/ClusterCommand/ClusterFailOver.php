<?php

namespace EasySwoole\Redis\CommandHandle\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandle\AbstractCommandHandle;
use EasySwoole\Redis\Response;

class ClusterFailOver extends AbstractCommandHandle
{
    public $commandName = 'ClusterFailOver';


    public function handelCommandData(...$data)
    {
        $option = array_shift($data);
        $command = [CommandConst::CLUSTER, 'FAILOVER'];
        if ($option){
            $command[]= $option;
        }
        $commandData = array_merge($command);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}
