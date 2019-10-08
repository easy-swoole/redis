<?php

namespace EasySwoole\Redis\CommandHandel\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandel\AbstractCommandHandel;
use EasySwoole\Redis\Response;

class ClusterAddSlots extends AbstractCommandHandel
{
    public $commandName = 'ClusterAddSlots';

    public function handelCommandData(...$data)
    {
        $slot = array_shift($data);
        $command = [CommandConst::CLUSTER, 'ADDSLOTS',$slot];
        $commandData = array_merge($command,$data);
        return $commandData;
    }


    public function handelRecv(Response $recv):bool
    {
        return $recv->getData();
    }
}
