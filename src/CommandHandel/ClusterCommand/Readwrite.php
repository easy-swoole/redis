<?php

namespace EasySwoole\Redis\CommandHandel\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandel\AbstractCommandHandel;
use EasySwoole\Redis\Response;

class Readwrite extends AbstractCommandHandel
{
    public $commandName = 'Readwrite';


    public function handelCommandData(...$data)
    {
        $command = [CommandConst::READWRITE];
        $commandData = array_merge($command);
        return $commandData;
    }


    public function handelRecv(Response $recv):bool
    {
        return true;
    }
}
