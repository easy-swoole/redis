<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ZInTerStore extends AbstractCommandHandel
{
    public $commandName = 'ZInTerStore';


    public function handelCommandData(...$data)
    {
        $destination = array_shift($data);
        $numKeys = array_shift($data);
        $key = array_shift($data);

        $command = [CommandConst::ZINTERSTORE, $destination, $numKeys, $key];
        $commandData = array_merge($command, $data);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}
