<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Response;

class HScan extends AbstractCommandHandel
{
    public  $commandName = 'HScan';

    public function handelCommandData(...$data)
    {
        $key=array_shift($data);
        $this->setClusterExecClientByKey($key);
        $cursor=array_shift($data);
        $pattern=array_shift($data);
        $count=array_shift($data);
        $command = [CommandConst::HSCAN,$key,$cursor];
        if ($pattern!==null){
            $command[] = 'MATCH';
            $command[] = $pattern;
        }
        if ($count!==null){
            $command[] = 'COUNT';
            $command[] = $count;
        }
        $commandData = $command;
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        $data = $recv->getData();
        $result = [];
        foreach ($data[1] as $k=>$value){
            if ($k%2==0){
                $result[$value] = [];
            }else{
                $result[$data[1][$k-1]]=$this->unSerialize($value);
            }
        }
        $data[1] = $result;
        return $data;
    }
}
