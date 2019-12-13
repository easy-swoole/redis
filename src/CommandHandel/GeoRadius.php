<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class GeoRadius extends AbstractCommandHandel
{
    public $commandName = 'GeoRadius';


    public function handelCommandData(...$data)
    {
        $key = array_shift($data);
        $this->setClusterExecClientByKey($key);
        $longitude = array_shift($data);
        $latitude = array_shift($data);
        $radius = array_shift($data);
        $unit = array_shift($data);
        $withCoord = array_shift($data);
        $withDist = array_shift($data);
        $withHash = array_shift($data);
        $count = array_shift($data);
        $sort = array_shift($data);
        $storeKey = array_shift($data);
        $storeDistKey = array_shift($data);



        $command = [CommandConst::GEORADIUS, $key, $longitude, $latitude, $radius, $unit,];

        if ($withCoord === true) {
            $command[] = 'WITHCOORD';
        }
        if ($withDist === true) {
            $command[] = 'WITHDIST';
        }
        if ($withHash === true) {
            $command[] = 'WITHHASH';
        }
        if ($count !== null) {
            $command[] = 'COUNT';
            $command[] = (int)$count;
        }
        if ($sort !== null) {
            $command[] = $sort;
        }
        if ($storeKey !== null) {
            $command[] = 'STORE';
            $command[] = $storeKey;
        }
        if ($storeDistKey !== null) {
            $command[] = 'STOREDIST';
            $command[] = $storeDistKey;
        }
        $commandData = array_merge($command, $data);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}
