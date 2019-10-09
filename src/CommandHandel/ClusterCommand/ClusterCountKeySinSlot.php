<?php

namespace EasySwoole\Redis\CommandHandel\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandel\AbstractCommandHandel;
use EasySwoole\Redis\Response;

class ClusterCountKeySinSlot extends AbstractCommandHandel
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
