<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Response;

class ZPopMax extends AbstractCommandHandel
{
    public $commandName = 'ZPopMax';
    
    public function handelCommandData(...$data)
    {
        $key = array_shift($data);
        $this->setClusterExecClientByKey($key);
        $count = array_shift($data);
        
        $commandData = [CommandConst::ZPOPMAX, $key];
        if (!is_null($count) && $count > 1) {
            $commandData[] = $count;
        }

        return $commandData;
    }
    
    
    public function handelRecv(Response $recv)
    {
        $data = $recv->getData();
        $result = [];

        foreach ($data as $index => $val) {
            if ($index % 2 == 1) {
                $result[$this->unSerialize($data[$index - 1])] = $val;
            }
        }
        
        return $result;
    }
}
