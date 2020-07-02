<?php
/**
 * @author gaobinzhan <gaobinzhan@gmail.com>
 */


namespace EasySwoole\Redis\CommandHandel;


use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Response;

class XGroup extends AbstractCommandHandel
{
    protected $commandName = 'XGROUP';

    public function handelCommandData(...$data)
    {
        $operation = array_shift($data);
        $key = array_shift($data);
        $group = array_shift($data);
        $msgId = array_shift($data);
        $mkStream = array_shift($data);
        $this->setClusterExecClientByKey($key);

        $command = [CommandConst::XGROUP];
        switch ($operation) {
            case 'CREATE':
            {
                $commandData = array_merge($command, [$operation, $key, $group, $msgId]);
                if ($mkStream) $commandData = array_merge($commandData, ['MKSTREAM']);
                break;
            }
            case 'SETID':
            case 'DELCONSUMER':
            {
                $commandData = array_merge($command, [$operation, $key, $group, $msgId]);
                break;
            }
            case 'DESTROY':
            {
                $commandData = array_merge($command, [$operation, $key, $group]);
                break;
            }
            case 'HELP':
            {
                $commandData = array_merge($command, [$operation]);
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
        if ($data === 'OK') {
            return true;
        }

        return $data;
    }
}