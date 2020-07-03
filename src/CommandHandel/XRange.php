<?php
/**
 * @author gaobinzhan <gaobinzhan@gmail.com>
 */


namespace EasySwoole\Redis\CommandHandel;


use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Response;

class XRange extends AbstractCommandHandel
{
    protected $commandName = 'XRANGE';

    public function handelCommandData(...$data)
    {
        $key = array_shift($data);
        $start = array_shift($data);
        $end = array_shift($data);
        $count = array_shift($data);
        $this->setClusterExecClientByKey($key);

        $commandData = [CommandConst::XRANGE, $key, $start, $end];
        if (!is_null($count) && is_int($count)) $commandData = array_merge($commandData, ['COUNT', $count]);
        return $commandData;
    }

    public function handelRecv(Response $recv)
    {
        $data = $recv->getData();

        $result = [];
        if (!is_array($data)) return false;

        foreach ($data as $v) {
            $id = $v[0];
            $values = $v[1];

            $result[$id] = array_combine(
                array_filter($values, function ($index) {
                    return (!($index & 1));
                }, ARRAY_FILTER_USE_KEY),
                array_filter($values, function ($index) {
                    return ($index & 1);
                }, ARRAY_FILTER_USE_KEY)
            );
        }

        if (empty($result)) return false;
        return $result;
    }
}