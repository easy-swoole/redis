<?php

namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class SRandMember extends AbstractCommandHandle
{
    public $commandName = 'SRandMemBer';


    public function handelCommandData(...$data)
    {
        $key = array_shift($data);
        $this->setClusterExecClientByKey($key);
        $count = array_shift($data);

        $command = [CommandConst::SRANDMEMBER, $key];
        if ($count !== null) {
            $command[] = $count;
        }
        $commandData = array_merge($command);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        $data = $recv->getData();
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->unSerialize($value);
            }
        }else{
            $data = $this->unSerialize($data);
        }

        return $data;
    }
}
