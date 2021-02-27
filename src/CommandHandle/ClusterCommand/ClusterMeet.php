<?php

namespace EasySwoole\Redis\CommandHandle\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandle\AbstractCommandHandle;
use EasySwoole\Redis\Response;

class ClusterMeet extends AbstractCommandHandle
{
    public $commandName = 'ClusterMeet';


    public function handelCommandData(...$data)
    {
        $ip = array_shift($data);
        $port = array_shift($data);
        $command = [CommandConst::CLUSTER, 'MEET', $ip,$port];
        $commandData = array_merge($command);
        return $commandData;
    }


    public function handelRecv(Response $recv):bool
    {
        return true;
    }
}
