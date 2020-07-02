<?php
/**
 * @author gaobinzhan <gaobinzhan@gmail.com>
 */


namespace EasySwoole\Redis\CommandHandel;


use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Response;

class XClaim extends AbstractCommandHandel
{
    protected $commandName = 'XCLAIM';

    public function handelCommandData(...$data)
    {
        $key = array_shift($data);
        $group = array_shift($data);
        $consumer = array_shift($data);
        $minIdleTime = array_shift($data);
        $ids = array_shift($data);
        $options = array_shift($data);
        $this->setClusterExecClientByKey($key);

        $command = [CommandConst::XCLAIM, $key, $group, $consumer, $minIdleTime];
        $commandData = array_merge($command, $ids);

        foreach ($options as $k => $option) {
            $commandData = array_merge($commandData, [$key, $option]);
        }

        return $commandData;
    }

    public function handelRecv(Response $recv)
    {
        $data = $recv->getData();
        if (!is_array($data)) return false;

        $result = [];
        foreach ($data as $item) {
            $id = $item[0];
            $values = $item[1];
            $result[$id] = array_combine(
                array_filter($values, function ($index) {
                    return (!($index & 1));
                }, ARRAY_FILTER_USE_KEY),
                array_filter($values, function ($index) {
                    return ($index & 1);
                }, ARRAY_FILTER_USE_KEY)
            );
        }
        return $result;
    }
}