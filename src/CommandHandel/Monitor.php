<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Monitor extends AbstractCommandHandel
{
    public $commandName = 'Monitor';
    protected $callback;

    public function handelCommandData(...$data)
    {
        $callback = array_shift($data);
        $this->callback = $callback;
        $command = [CommandConst::MONITOR];
        $commandData = array_merge($command, $data);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        $this->redis->setMonitorStop(false);
        while ($this->redis->isMonitorStop() == false) {
            $recv = $this->redis->recv(-1);
            if ($recv === null) {
                return false;
            }
            call_user_func($this->callback, $this->redis, $recv->getData());
        }
    }
}
