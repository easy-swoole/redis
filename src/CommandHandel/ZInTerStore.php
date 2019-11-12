<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Exception\RedisClusterException;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ZInTerStore extends AbstractCommandHandel
{
    public $commandName = 'ZInTerStore';


    public function handelCommandData(...$data)
    {
        $destination = array_shift($data);
        $keys = array_shift($data);
        $weights = array_shift($data);
        $aggregate = array_shift($data);
        $keysCount = count($keys);
        $weightsCount = count($weights);
        if ($weightsCount > 0 && $keysCount !== $weightsCount) {
            throw new RedisClusterException('WEIGHTS and keys array should be the same size!');
        }

        $command = [CommandConst::ZINTERSTORE, $destination, $keysCount];
        foreach ($keys as $k => $key) {
            $command[] = $key;
        }
        if ($weightsCount > 0) {
            $command[] = 'WEIGHTS';
        }
        foreach ($weights as $k => $weight) {
            $command[] = $weight;
        }
        $command[] = 'AGGREGATE';
        $command[] = $aggregate;
        $commandData = array_merge($command);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}
