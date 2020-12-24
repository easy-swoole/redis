<?php

namespace EasySwoole\Redis\CommandHandel\ClusterCommand;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\CommandHandel\AbstractCommandHandel;
use EasySwoole\Redis\Response;

class ClusterNodes extends AbstractCommandHandel
{
    public $commandName = 'ClusterNodes';


    public function handelCommandData(...$data)
    {
        $command = [CommandConst::CLUSTER, 'NODES'];
        $commandData = array_merge($command);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        $list = explode(PHP_EOL, $recv->getData());
        $nodeList = [];
        foreach ($list as $serverData) {
            $data = explode(' ', $serverData);
            if (empty($data[0])) {
                continue;
            }
            list($host, $port) = explode(':', explode('@', $data[1])[0]);
            $node = [
                'name'      => $data[0],
                'host'      => $host,
                'port'      => $port,
                'flags'     => $data[2],
                'connected' => $data[7],
                'slotList'  => $this->getSlotList($data),
            ];
            $nodeList[$node['name']] = $node;
        }
        return $nodeList;
    }

    protected function getSlotList($data)
    {
        $slotList = [];
        //获取8后面的数据
        $slotOriginalData = array_slice($data, 8);
        foreach ($slotOriginalData as $slotString) {
            $slotList[] = explode('-', $slotString);
        }
        return $slotList;
    }
}
