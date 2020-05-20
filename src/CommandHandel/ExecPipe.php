<?php

namespace EasySwoole\Redis\CommandHandel;

use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Redis;
use EasySwoole\Redis\Response;

class ExecPipe extends AbstractCommandHandel
{
    public $commandName = 'ExecPipe';


    public function handelCommandData(...$data)
    {
        $commandLog = $this->redis->getPipe()->getCommandLog();
        $this->redis->getPipe()->setIsStartPipe(false);
        $commandData = '';
        foreach ($commandLog as $command) {
            //兼容hook event
            $this->onBeforeEvent($command[1]);

            $argNum = count($command[1]);
            $str = "*{$argNum}\r\n";
            foreach ($command[1] as $value) {
                $len = strlen($value);
                $str = $str . '$' . "{$len}\r\n{$value}\r\n";
            }

            $commandData .= "{$str}";
        }
        return $commandData;
    }


    public function handelRecv(Response $recv)
    {
        $commandLog = $this->redis->getPipe()->getCommandLog();
        $this->redis->getPipe()->setCommandLog([]);
        $data = [];
        foreach ($commandLog as $k => $command) {
            $commandClassName = "\\EasySwoole\\Redis\\CommandHandel\\" . $command[0];
            /**
             * @var $commandClass AbstractCommandHandel
             */
            $commandClass = new $commandClassName($this->redis);
            //兼容hook event
            $commandClass->setCommandData($command[1]);
            //获取返回tcp数据
            $response = $this->redis->recv($this->redis->getConfig()->getTimeout());
            $data[$k] = $commandClass->getData($response);
        }
        return $data;
    }
}
