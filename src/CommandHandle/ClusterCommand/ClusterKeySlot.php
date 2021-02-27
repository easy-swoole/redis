<?php

namespace EasySwoole\Redis\CommandHandle\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandle\AbstractCommandHandle;
use EasySwoole\Redis\Response;

class ClusterKeySlot extends AbstractCommandHandle
{
    public $commandName = 'ClusterKeySlot';


    public function handelCommandData(...$data)
    {
        $key = array_shift($data);
        $command = [CommandConst::CLUSTER, 'KEYSLOT', $key];
        $commandData = array_merge($command);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}
