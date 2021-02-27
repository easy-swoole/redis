<?php

namespace EasySwoole\Redis\CommandHandle\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandle\AbstractCommandHandle;
use EasySwoole\Redis\Response;

class ClusterSaveConfig extends AbstractCommandHandle
{
    public $commandName = 'ClusterSaveConfig';


    public function handelCommandData(...$data)
    {
        $command = [CommandConst::CLUSTER, 'SAVECONFIG'];
        $commandData = array_merge($command);
        return $commandData;
    }


    public function handelRecv(Response $recv):bool
    {
        return true;
    }
}
