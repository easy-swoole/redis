<?php


namespace EasySwoole\Redis\Config;


use EasySwoole\Spl\SplBean;

class RedisConfig extends SplBean
{
    public const SERIALIZE_NONE = 0;
    public const SERIALIZE_PHP = 1;
    public const SERIALIZE_JSON = 2;
    protected $host='127.0.0.1';
    protected $port = 6379;
    protected $auth;
    protected $timeout = 3.0;
    protected $reconnectTimes = 3;
    protected $db=null;
    protected $serialize = self::SERIALIZE_NONE;

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
}