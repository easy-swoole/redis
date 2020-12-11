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
        
        $this->count = $count;
        $command = [CommandConst::ZPOPMAX, $key];
        if (!is_null($count) && $count > 1) {
            $command[] = $count;
        }
        $commandData = array_merge($command);
        return $commandData;
    }
    
    
    public function handelRecv(Response $recv)
    {
        $data = $recv->getData();
        $result = [];
        foreach ($data as $k => $va) {
            if ($k % 2 == 1) {
                $result[$this->unSerialize($va)] = $this->unSerialize($data[$k - 1]);
            }
        }
        
        return $result;
    }
}
