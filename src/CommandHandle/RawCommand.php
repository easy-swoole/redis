<?php

namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class RawCommand extends AbstractCommandHandle
{
    public $commandName = 'RawCommand';


    public function handelCommandData(...$data)
    {
        $commandData = array_shift($data);

        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}
