<?php

namespace EasySwoole\Redis\CommandHandel\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandel\AbstractCommandHandel;
use EasySwoole\Redis\Response;

class ClusterMeet extends AbstractCommandHandel
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
