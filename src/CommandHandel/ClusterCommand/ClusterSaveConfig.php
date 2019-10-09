<?php

namespace EasySwoole\Redis\CommandHandel\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandel\AbstractCommandHandel;
use EasySwoole\Redis\Response;

class ClusterSaveConfig extends AbstractCommandHandel
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
