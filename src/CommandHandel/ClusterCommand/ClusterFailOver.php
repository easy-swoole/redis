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
        $option = array_shift($data);
        $command = [CommandConst::CLUSTER, 'FAILOVER'];
        if ($option){
            $command[]= $option;
        }
        $commandData = array_merge($command);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}
