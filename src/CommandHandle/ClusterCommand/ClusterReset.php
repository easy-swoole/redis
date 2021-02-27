<?php

namespace EasySwoole\Redis\CommandHandle\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandle\AbstractCommandHandle;
use EasySwoole\Redis\Response;

class ClusterReset extends AbstractCommandHandle
{
    public $commandName = 'ClusterReset';


    public function handelCommandData(...$data)
    {
        $option = array_shift($data);
        $command = [CommandConst::CLUSTER, 'RESET'];
        if ($option){
            $command[] = $option;
        }
        $commandData = array_merge($command,$data);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}
