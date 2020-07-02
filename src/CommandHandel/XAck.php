<?php
/**
 * @author gaobinzhan <gaobinzhan@gmail.com>
 */


namespace EasySwoole\Redis\CommandHandel;


use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Response;

class XAck extends AbstractCommandHandel
{
    protected $commandName = 'XACK';

    public function handelCommandData(...$data)
    {
        $key = array_shift($data);
        $group = array_shift($data);
        $ids = array_shift($data);
        $this->setClusterExecClientByKey($key);

        $command = [CommandConst::XACK, $key, $group];

        $commandData = array_merge($command, $ids);

        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}