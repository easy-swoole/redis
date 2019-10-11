<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Response;

class Scan extends AbstractCommandHandel
{
    public  $commandName = 'Scan';

    public function handelCommandData(...$data)
    {
        $cursor=array_shift($data);
        $pattern=array_shift($data);
        $count=array_shift($data);
        $command = [CommandConst::SCAN,$cursor];
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
        return $recv->getData();
    }
}
