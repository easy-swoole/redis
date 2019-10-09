<?php

namespace EasySwoole\Redis\CommandHandel\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandel\AbstractCommandHandel;
use EasySwoole\Redis\Response;

class ClusterForget extends AbstractCommandHandel
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
