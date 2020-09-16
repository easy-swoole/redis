<?php
/**
 * @author gaobinzhan <gaobinzhan@gmail.com>
 */


namespace EasySwoole\Redis\CommandHandel;


use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Response;

class BitPos extends AbstractCommandHandel
{
    protected $commandName = 'BITPOS';

    public function handelCommandData(...$data)
    {
        $key = array_shift($data);
        $this->setClusterExecClientByKey($key);
        $bit = array_shift($data);
        $start = array_shift($data);
        $end = array_shift($data);

        $commandData = [CommandConst::BITPOS, $key, $bit];

        if ($start !== null) {
            $commandData = array_merge($commandData, [$start]);
            if ($end !== null) {
                $commandData = array_merge($commandData, [$end]);
            }
        }
        return $commandData;
    }

    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}