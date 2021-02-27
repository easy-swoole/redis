<?php

namespace EasySwoole\Redis\CommandHandle;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Response;

class SPop extends AbstractCommandHandle
{
    public $commandName = 'SPop';

    protected $count = 1;

    public function handelCommandData(...$data)
    {
        $key = array_shift($data);
        $this->setClusterExecClientByKey($key);
        $count = array_shift($data);

        $command = [CommandConst::SPOP, $key];
        if (!is_null($count) && $count > 1) {
            $command[] = $count;
            $this->count = $count;
        }

        return $command;
    }


    public function handelRecv(Response $recv)
    {

        $data = $recv->getData();
        if ($this->count > 1) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->unSerialize($value);
            }
            return $data;
        } else {
            return $this->unSerialize($data);
        }
    }

}
