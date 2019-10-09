<?php
/**
 * Created by PhpStorm.
 * User: tioncico
 * Date: 19-10-4
 * Time: 下午3:37
 */

namespace EasySwoole\Redis;


class Pipe
{
    protected $commandLog = [];
    protected $isStartPipe = false;

    const IGNORE_COMMAND = [
        'discard',
        'exec',
        'multi',
//        'unWatch',
//        'watch',
    ];

    /**
     * @return array
     */
    public function getCommandLog(): array
    {
        return $this->commandLog;
    }

    /**
     * @param array $commandLog
     */
    public function setCommandLog(array $commandLog): void
    {
        $this->commandLog = $commandLog;
    }

    public function addCommand($command)
    {
        $this->commandLog[] = $command;
    }

    /**
     * @return bool
     */
    public function isStartPipe(): bool
    {
        return $this->isStartPipe;
    }

    /**
     * @param bool $isStartPipe
     */
    public function setIsStartPipe(bool $isStartPipe): void
    {
        $this->isStartPipe = $isStartPipe;
    }

}