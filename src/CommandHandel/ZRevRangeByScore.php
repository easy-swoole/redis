<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ZRevRangeByScore extends AbstractCommandHandel
{
    public $commandName = 'ZRevRangeByScore';
    protected $withScores = false;


    public function handelCommandData(...$data)
    {
        $key = array_shift($data);
        $this->setClusterExecClientByKey($key);
        $max = array_shift($data);
        $min = array_shift($data);
        $options = array_shift($data);
        $this->withScores = $options['withScores']??false;

        $command = [CommandConst::ZREVRANGEBYSCORE, $key, $max, $min];
        if ($this->withScores == true) {
            $command[] = 'WITHSCORES';
        }
        if (!empty($options['limit'])&&is_array($options['limit'])){
            $command[] = 'LIMIT';
            $command[]= $options['limit'][0];
            $command[]= $options['limit'][1];
        }
        $commandData = array_merge($command);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        $data = $recv->getData();
        if ($this->withScores == true) {
            $result = [];
            foreach ($data as $k => $va) {
                if ($k % 2 == 0) {
                    $result[$this->unSerialize($va)] = 0;
                } else {
                    $result[$this->unSerialize($data[$k - 1])] = $va;
                }
            }
        } else {
            $result = [];
            foreach ($data as $k => $va) {
                $result[$k] = $this->unSerialize($va);
            }
        }
        return $result;
    }
}
