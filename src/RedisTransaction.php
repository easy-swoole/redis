<?php
/**
 * Created by PhpStorm.
 * User: tioncico
 * Date: 19-10-4
 * Time: 下午3:37
 */

namespace EasySwoole\Redis;


class RedisTransaction
{
    protected $commandLog = [];
    protected $isTransaction = false;

    const TRANSACTION_COMMAND = [
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

    function addCommand($command)
    {
        $this->commandLog[] = $command;
    }

    /**
     * @return bool
     */
    public function isTransaction(): bool
    {
        return $this->isTransaction;
    }

    /**
     * @param bool $isTransaction
     */
    public function setIsTransaction(bool $isTransaction): void
    {
        $this->isTransaction = $isTransaction;
    }
}