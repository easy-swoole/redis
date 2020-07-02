<?php
/**
 * @author gaobinzhan <gaobinzhan@gmail.com>
 */


namespace EasySwoole\Redis\CommandHandel;


use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Response;

class XDel extends AbstractCommandHandel
{
    protected $commandName = 'XLEN';

    public function handelCommandData(...$data)
    {
        $key = array_shift($data);
        $ids = array_shift($data);
        $this->setClusterExecClientByKey($key);

        $command = [CommandConst::XDEL, $key];
        $commandData = array_merge($command, $ids);
        return $commandData;
    }

    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}