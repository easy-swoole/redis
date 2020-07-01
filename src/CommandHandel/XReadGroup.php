<?php
/**
 * @author gaobinzhan <gaobinzhan@gmail.com>
 */


namespace EasySwoole\Redis\CommandHandel;


use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Response;

class XReadGroup extends AbstractCommandHandel
{
    protected $commandName = 'XREAD';

    public function handelCommandData(...$data)
    {
        $group = array_shift($data);
        $consumer = array_shift($data);
        $streams = array_shift($data);
        $count = array_shift($data);
        $block = array_shift($data);
        $commandData = [CommandConst::XREADGROUP, 'GROUP', $group, $consumer];

        if (!is_null($count) && is_int($count)) {
            $commandData = array_merge($commandData, ['COUNT', $count]);
        }

        if (!is_null($block) && is_int($block)) {
            $commandData = array_merge($commandData, ['BLOCK', $block]);
        }

        return array_merge($commandData, ['STREAMS'], array_keys($streams), array_values($streams));
    }

    public function handelRecv(Response $recv)
    {
        $data = $recv->getData();
        $result = [];
        if (!is_array($data)) return false;

        foreach ($data as $stream) {
            $key = $stream[0];
            $items = $stream[1];
            foreach ($items as $item) {
                $id = $item[0];
                $values = $item[1];
                $result[$key][$id] = array_combine(
                    array_filter($values, function ($index) {
                        return (!($index & 1));
                    }, ARRAY_FILTER_USE_KEY),
                    array_filter($values, function ($index) {
                        return ($index & 1);
                    }, ARRAY_FILTER_USE_KEY)
                );
            }
        }

        return $result;
    }
}