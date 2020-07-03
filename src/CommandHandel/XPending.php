<?php
/**
 * @author gaobinzhan <gaobinzhan@gmail.com>
 */


namespace EasySwoole\Redis\CommandHandel;


use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Response;

class XPending extends AbstractCommandHandel
{
    protected $commandName = 'XPENDING';

    public function handelCommandData(...$data)
    {
        $stream = array_shift($data);
        $group = array_shift($data);
        $start = array_shift($data);
        $end = array_shift($data);
        $count = array_shift($data);
        $consumer = array_shift($data);
        $this->setClusterExecClientByKey($stream);

        $commandData = [CommandConst::XPENDING, $stream, $group, $start, $end, $count, $consumer];
        return array_filter($commandData);
    }

    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}