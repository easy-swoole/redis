<?php

namespace EasySwoole\Redis\CommandHandel\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandel\AbstractCommandHandel;
use EasySwoole\Redis\Response;

class ClusterKeySlot extends AbstractCommandHandel
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
