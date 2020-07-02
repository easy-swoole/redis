<?php
/**
 * @author gaobinzhan <gaobinzhan@gmail.com>
 */


namespace EasySwoole\Redis\CommandHandel;


use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Response;

class XAdd extends AbstractCommandHandel
{
    protected $commandName = 'XADD';

    public function handelCommandData(...$data)
    {
        $key = array_shift($data);
        $id = array_shift($data);
        $messages = array_shift($data);
        $maxLen = array_shift($data);
        $isApproximate = array_shift($data);
        $this->setClusterExecClientByKey($key);

        $commandData = [CommandConst::XADD, $key];

        if (!is_null($maxLen) && is_int($maxLen)) {
            if ($isApproximate) {
                $commandData = array_merge($commandData, ['MAXLEN', '~', $maxLen]);
            } else {
                $commandData = array_merge($commandData, ['MAXLEN', $maxLen]);
            }
        }

        $commandData = array_merge($commandData, [$id]);

        foreach ($messages as $k => $v) {
            $commandData = array_merge($commandData, [$k, $v]);
        }
        return $commandData;
    }

    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}