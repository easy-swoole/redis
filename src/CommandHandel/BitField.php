<?php
/**
 * @author gaobinzhan <gaobinzhan@gmail.com>
 */


namespace EasySwoole\Redis\CommandHandel;


use EasySwoole\Redis\CommandConst;
use EasySwoole\Redis\Response;

class BitField extends AbstractCommandHandel
{
    protected $commandName = 'BITFIELD';

    public function handelCommandData(...$data)
    {
        $key = array_shift($data);
        $this->setClusterExecClientByKey($key);
        $subcommands = array_shift($data);
        $overflow = array_shift($data);
        $subcommandArgs = array_shift($data);

        $commandData = [CommandConst::BITFIELD, $key];

        if (count($subcommands) === count($subcommands, 1)) {
            $commandData = array_merge($commandData, $subcommands);
        } else {
            foreach ($subcommands as $subcommand) {
                $commandData = array_merge($commandData, $subcommand);
            }
        }

        if ($overflow !== null) {
            $commandData = array_merge($commandData, ['OVERFLOW', $overflow]);
            if (count($subcommandArgs) === count($subcommandArgs, 1)) {
                $commandData = array_merge($commandData, $subcommandArgs);
            } else {
                foreach ($subcommandArgs as $subcommandArg) {
                    $commandData = array_merge($commandData, $subcommandArg);
                }
            }
        }

        return $commandData;
    }

    public function handelRecv(Response $recv)
    {
        return $recv->getData();
    }
}