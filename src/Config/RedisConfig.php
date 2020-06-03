<?php


namespace EasySwoole\Redis\Config;


use EasySwoole\Spl\SplBean;

class RedisConfig extends SplBean
{
    public const SERIALIZE_NONE = 0;
    public const SERIALIZE_PHP = 1;
    public const SERIALIZE_JSON = 2;
    protected $host = '127.0.0.1';
    protected $port = 6379;
    protected $unixSocket = null;
    protected $auth;
    protected $timeout = 3.0;
    protected $reconnectTimes = 3;
    protected $db = null;
    protected $serialize = self::SERIALIZE_NONE;
    protected $beforeEvent = null;
    protected $afterEvent = null;

    protected $packageMaxLength = 1024 * 1024 * 2;


    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param mixed $host
     */
    public function setHost($host): void
    {
        $this->host = $host;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @param int $port
     */
    public function setPort(int $port): void
    {
        $this->port = $port;
    }

    /**
     * @return mixed
     */
    public function getAuth()
    {
        return $this->auth;
    }

    /**
     * @param mixed $auth
     */
    public function setAuth($auth): void
    {
        $this->auth = $auth;
    }

    /**
     * @return float
     */
    public function getTimeout(): float
    {
        return $this->timeout;
    }

    /**
     * @param float $timeout
     */
    public function setTimeout(float $timeout): void
    {
        $this->timeout = $timeout;
    }

    /**
     * @return int
     */
    public function getReconnectTimes(): int
    {
        return $this->reconnectTimes;
    }

    /**
     * @param int $reconnectTimes
     */
    public function setReconnectTimes(int $reconnectTimes): void
    {
        $this->reconnectTimes = $reconnectTimes;
    }

    /**
     * @return int
     */
    public function getSerialize(): int
    {
        return $this->serialize;
    }

    /**
     * @param int $serialize
     */
    public function setSerialize(int $serialize): void
    {
        $this->serialize = $serialize;
    }

    /**
     * @return int
     */
    public function getDb(): ?int
    {
        return $this->db;
    }

    /**
     * @param int $db
     */
    public function setDb(?int $db): void
    {
        $this->db = $db;
    }

    /**
     * @param callable $beforeEvent
     */
    public function onBeforeEvent(callable $beforeEvent): void
    {
        $this->beforeEvent = $beforeEvent;
    }

    /**
     * @param callable $afterEvent
     */
    public function onAfterEvent(callable $afterEvent): void
    {
        $this->afterEvent = $afterEvent;
    }

    /**
     * @return null
     */
    public function getBeforeEvent()
    {
        return $this->beforeEvent;
    }

    /**
     * @return null
     */
    public function getAfterEvent()
    {
        return $this->afterEvent;
    }

    /**
     * @return null
     */
    public function getUnixSocket()
    {
        return $this->unixSocket;
    }

    /**
     * @param null $unixSocket
     */
    public function setUnixSocket($unixSocket): void
    {
        $this->unixSocket = $unixSocket;
    }

    /**
     * @return float|int
     */
    public function getPackageMaxLength()
    {
        return $this->packageMaxLength;
    }

    /**
     * @param float|int $packageMaxLength
     */
    public function setPackageMaxLength($packageMaxLength): void
    {
        $this->packageMaxLength = $packageMaxLength;
    }

}
