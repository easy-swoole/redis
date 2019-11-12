<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ClientList extends AbstractCommandHandel
{
    public $commandName = 'ClientList';


    public function handelCommandData(...$data)
    {

        $command = [CommandConst::CLIENT, 'LIST'];
        $commandData = array_merge($command, $data);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        $data = $recv->getData();
        $result = [];
        foreach (explode(PHP_EOL, $data) as $clientKey => $value) {
            if (empty($value)) {
                continue;
            }
            $arr = explode(' ', $value);
            $result[$clientKey] = [];
            foreach ($arr as $kv) {
                $kvArr = explode('=', $kv);
                $result[$clientKey][$kvArr[0]] = $kvArr[1];
            }
        }
        return $result;
    }
}
