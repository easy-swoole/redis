<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Response;

class SScan extends AbstractCommandHandel
{
    public  $commandName = 'SScan';

    public function handelCommandData(...$data)
    {
        $key=array_shift($data);
        $this->setClusterExecClientByKey($key);
        $cursor=array_shift($data);
        $pattern=array_shift($data);
        $count=array_shift($data);
        $command = [CommandConst::SSCAN,$key,$cursor];
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
        foreach ($data[1] as $k=>$value){
            $data[1][$k] = $this->unSerialize($value);
        }
        return $data;
    }
}
