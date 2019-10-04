<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Subscribe extends AbstractCommandHandel
{
    public $commandName = 'Subscribe';
    protected $callback;


    public function handelCommandData(...$data)
    {
        $callback = array_shift($data);
        $channel = array_shift($data);
        $this->callback = $callback;
        $command = [CommandConst::SUBSCRIBE, $channel];
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
            if ($recv->getData()[0] == 'message') {
                call_user_func($this->callback, $this->redis, $recv->getData()[1], $recv->getData()[2]);
            }
        }
    }
}
