<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class PSubscribe extends AbstractCommandHandel
{
    public $commandName = 'PSubscribe';
    protected $callback;

    public function handelCommandData(...$data)
    {
        $callback = array_shift($data);
        $pattern = array_shift($data);
        $this->callback = $callback;
        $command = [CommandConst::PSUBSCRIBE, $pattern];
        $commandData = array_merge($command, $data);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        $this->redis->setSubscribeStop(false);
        while ($this->redis->isSubscribeStop() == false) {
            $recv = $this->redis->recv(-1);
            if ($recv === null) {
                return false;
            }
            if ($recv->getData()[0] == 'pmessage') {
                call_user_func($this->callback, $this->redis, $recv->getData()[2], $recv->getData()[3]);
            }
        }
    }
}
