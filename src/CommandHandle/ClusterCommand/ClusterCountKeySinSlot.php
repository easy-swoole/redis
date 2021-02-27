<?php

namespace EasySwoole\Redis\CommandHandle\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandle\AbstractCommandHandle;
use EasySwoole\Redis\Response;

class ClusterCountKeySinSlot extends AbstractCommandHandle
{
    public $commandName = 'ClusterCountKeySinSlot';

    public function handelCommandData(...$data)
    {
        $slot = array_shift($data);
        $command = [CommandConst::CLUSTER, 'COUNTKEYSINSLOT',$slot];
        $commandData = array_merge($command,$data);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}
