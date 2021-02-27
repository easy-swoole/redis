<?php

namespace EasySwoole\Redis\CommandHandle\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandle\AbstractCommandHandle;
use EasySwoole\Redis\Response;

class ClusterSetSlot extends AbstractCommandHandle
{
    public $commandName = 'ClusterSetSlot';


    public function handelCommandData(...$data)
    {
        $slot  = array_shift($data);
        $subCommand  = array_shift($data);
        $nodeId  = array_shift($data);
        $command = [CommandConst::CLUSTER, 'SETSLOT', $slot, $subCommand];
        if ($nodeId){
            $command[] = $nodeId;
        }
        $commandData = array_merge($command);
        return $commandData;
    }


    public function handelRecv(Response $recv):bool
    {
        return true;
    }
}
