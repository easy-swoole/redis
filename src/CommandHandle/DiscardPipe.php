<?php

namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class DiscardPipe extends AbstractCommandHandle
{
    public $commandName = 'DiscardPipe';


    public function handelCommandData(...$data)
    {

        $this->redis->getPipe()->setIsStartPipe(false);
        return true;
    }


    public function handelRecv(Response $recv)
    {
        $this->redis->getPipe()->setCommandLog([]);
        return true;
    }
}
