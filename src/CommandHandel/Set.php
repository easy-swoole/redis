<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Set extends AbstractCommandHandel
{
    public $commandName = 'Set';

    public function handelCommandData(...$data)
    {
        $key = array_shift($data);
        $this->setClusterExecClientByKey($key);
        $val = array_shift($data);
        $timeout = array_shift($data);
        $val = $this->serialize($val);
        $command = [CommandConst::SET, $key, $val];
        //int的时候
        if (is_int($timeout) && $timeout > 0) {
            $command[] = 'EX';
            $command[] = $timeout;
        }
        //string的时候
        if (is_string($timeout) && in_array($timeout, ['NX', 'XX'])) {
            $command[] = $timeout;
        }
        //传入数组
        if (is_array($timeout)) {
            if (!empty($timeout['EX'])) {
                $command[] = 'EX';
                $command[] = $timeout['EX'];
                unset($timeout['EX']);
            }
            if (!empty($timeout['PX'])) {
                $command[] = 'PX';
                $command[] = $timeout['PX'];
                unset($timeout['PX']);
            }
            foreach ($timeout as $v) {
                $command[] = $v;
            }
        }
        $commandData = $command;
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        if ($recv->getData() === null) {
            return null;
        } else {
            return true;
        }
    }
}
