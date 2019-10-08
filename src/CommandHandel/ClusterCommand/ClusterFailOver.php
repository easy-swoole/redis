<?php

namespace EasySwoole\Redis\CommandHandel\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandel\AbstractCommandHandel;
use EasySwoole\Redis\Response;

class ClusterFailOver extends AbstractCommandHandel
{
    public $commandName = 'ClusterFailOver';


    public function handelCommandData(...$data)
    {
        $slot = array_shift($data);
        $command = [CommandConst::CLUSTER, 'FAILOVER', $slot];
        $commandData = array_merge($command, $data);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}
