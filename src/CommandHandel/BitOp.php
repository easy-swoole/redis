<?php
/**
 * @author gaobinzhan <gaobinzhan@gmail.com>
 */


namespace EasySwoole\Redis\CommandHandel;


use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Response;

class BitOp extends AbstractCommandHandel
{
    protected $commandName = 'BITOP';

    public function handelCommandData(...$data)
    {
        $operation = array_shift($data);
        $destKey = array_shift($data);
        $this->setClusterExecClientByKey($destKey);
        $key = array_shift($data);
        $otherKeys = array_shift($data);

        $commandData = [CommandConst::BITOP, $operation, $destKey, $key];

        if ($otherKeys) {
            $commandData = array_merge($commandData, $otherKeys);
        }
        return $commandData;
    }

    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}