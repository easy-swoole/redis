<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class SRandMemBer extends AbstractCommandHandel
{
    public $commandName = 'SRandMemBer';


    public function getCommand(...$data)
    {
        $key = array_shift($data);
        $count = array_shift($data);

        $command = [CommandConst::SRANDMEMBER, $key];
        if ($count !== null) {
            $command[] = $count;
        }
        $commandData = array_merge($command);
        return $commandData;
    }


    public function getData(Response $recv)
    {
        $data = $recv->getData();
        foreach ($data as $key => $value) {
            $data[$key] = $this->unSerialize($value);
        }
        return $data;
    }
}
