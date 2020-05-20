<?php

namespace EasySwoole\Redis\CommandHandel\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandel\AbstractCommandHandel;
use EasySwoole\Redis\Response;

class ClusterSlaves extends AbstractCommandHandel
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
