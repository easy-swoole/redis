<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class Info extends AbstractCommandHandel
{
    public $commandName = 'Info';


    public function handelCommandData(...$data)
    {
        $section = array_shift($data);

        if ($section != null) {
            $data[] = $section;
        }

        $command = [CommandConst::INFO];
        $commandData = array_merge($command, $data);
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        $data = $recv->getData();
        $result = [];
        foreach (explode('# ', $data) as $value) {
            if (empty($value)) {
                continue;
            }
            $arr = explode("\r\n", $value);
            $sectionKey = array_shift($arr);
            $result[$sectionKey] = [];
            foreach ($arr as $kv) {
                if (empty($kv)) {
                    continue;
                }
                $kvArr = explode(':', $kv);
                $result[$sectionKey][$kvArr[0]] = $kvArr[1];
            }
        }

        return $result;
    }
}
