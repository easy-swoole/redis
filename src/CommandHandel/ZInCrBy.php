<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ZInCrBy extends AbstractCommandHandel {
    public $commandName = 'ZInCrBy';

    public function handelCommandData(...$data) {
        $key = array_shift($data);
        $this->setClusterExecClientByKey($key);
        $increment   = array_shift($data);
        $member      = array_shift($data);
        $member      = $this->serialize($member);
        $command     = [CommandConst::ZINCRBY, $key, $increment, $member];
        $commandData = array_merge($command, $data);

        return $commandData;
    }

    public function handelRecv(Response $recv) {
        return $recv->getData();
    }
}
