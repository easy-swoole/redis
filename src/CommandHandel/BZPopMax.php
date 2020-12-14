<?php


namespace EasySwoole\Redis\CommandHandel;


use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Response;

class BZPopMax extends AbstractCommandHandel
{
    public $commandName = 'BZPOPMAX';

    public function handelCommandData(...$data)
    {
        $keys = array_shift($data);
        $keys = is_array($keys) ? $keys : [$keys];
        $timeout = array_shift($data);

        return array_merge([CommandConst::BZPOPMAX], $keys, [$timeout]);
    }

    public function handelRecv(Response $recv)
    {
        $data = $recv->getData();

        if ($data) {
            $data[1] = $this->unSerialize($data[1]);
        }

        return $data;
    }
}