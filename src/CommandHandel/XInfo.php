<?php
/**
 * @author gaobinzhan <gaobinzhan@gmail.com>
 */


namespace EasySwoole\Redis\CommandHandel;


use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Response;

class XInfo extends AbstractCommandHandel
{
    protected $commandName = 'XINFO';

    public function handelCommandData(...$data)
    {
        $operation = array_shift($data);
        $key = array_shift($data);
        $group = array_shift($data);
        $this->setClusterExecClientByKey($key);

        $command = [CommandConst::XINFO];

        switch ($operation) {
            case 'STREAM':
            case 'GROUPS':
            {
                $commandData = array_merge($command, [$operation, $key]);
                break;
            }
            case 'CONSUMERS':
            {
                $commandData = array_merge($command, [$operation, $key, $group]);
                break;
            }
            default:
            {
                $commandData = $command;
                break;
            }
        }

        return $commandData;
    }

    public function handelRecv(Response $recv)
    {
        $data = $recv->getData();
        if (!is_array($data)) return false;

        $result = [];
        if (count($data) == 14) {
            for ($i = 0; $i < 14;) {
                $result[$data[$i]] = $data[$i + 1];
                $i += 2;
            }

            $firstEntryKey = $result['first-entry'][0] ?? '';
            $firstEntryValues = $result['first-entry'][1] ?? '';
            if (is_array($result['first-entry'])) {
                $result['first-entry'] = [
                    $firstEntryKey => array_combine(
                        array_filter($firstEntryValues, function ($index) {
                            return (!($index & 1));
                        }, ARRAY_FILTER_USE_KEY),
                        array_filter($firstEntryValues, function ($index) {
                            return ($index & 1);
                        }, ARRAY_FILTER_USE_KEY))
                ];
            }

            $lastEntryKey = $result['last-entry'][0] ?? '';
            $lastEntryValues = $result['last-entry'][1] ?? '';
            if (is_array($result['last-entry'])) {
                $result['last-entry'] = [
                    $lastEntryKey => array_combine(
                        array_filter($lastEntryValues, function ($index) {
                            return (!($index & 1));
                        }, ARRAY_FILTER_USE_KEY),
                        array_filter($lastEntryValues, function ($index) {
                            return ($index & 1);
                        }, ARRAY_FILTER_USE_KEY))
                ];
            }
        } else {
            foreach ($data as $group) {
                $result[] = array_combine(
                    array_filter($group, function ($index) {
                        return (!($index & 1));
                    }, ARRAY_FILTER_USE_KEY),
                    array_filter($group, function ($index) {
                        return ($index & 1);
                    }, ARRAY_FILTER_USE_KEY));
            }
        }

        if (empty($result)) return false;
        return $result;
    }
}