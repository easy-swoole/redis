<?php

namespace EasySwoole\Redis\CommandHandle\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandle\AbstractCommandHandle;
use EasySwoole\Redis\Response;

class ClusterGetKeySinSlot extends AbstractCommandHandle
{
    public $commandName = 'ClusterGetKeySinSlot';


    public function handelCommandData(...$data)
    {
        $slot = array_shift($data);
        $count = array_shift($data);
        $command = [CommandConst::CLUSTER, 'GETKEYSINSLOT',$slot,$count];
        $commandData = array_merge($command);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}
