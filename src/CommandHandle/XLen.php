<?php
/**
 * @author gaobinzhan <gaobinzhan@gmail.com>
 */


namespace EasySwoole\Redis\CommandHandle;


use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Response;

class XLen extends AbstractCommandHandle
{
    protected $commandName = 'XLEN';

    public function handelCommandData(...$data)
    {
        $key = array_shift($data);
        $this->setClusterExecClientByKey($key);

        $commandData = [CommandConst::XLEN, $key];
        return $commandData;
    }

    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}
