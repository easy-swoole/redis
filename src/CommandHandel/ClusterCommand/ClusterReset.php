<?php

namespace EasySwoole\Redis\CommandHandel\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandel\AbstractCommandHandel;
use EasySwoole\Redis\Response;

class ClusterReset extends AbstractCommandHandel
{
    public $commandName = 'ClusterReset';


    public function handelCommandData(...$data)
    {
        $nodeId = array_shift($data);
        $command = [CommandConst::CLUSTER, 'RESET', $nodeId];
        $commandData = array_merge($command,$data);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}
