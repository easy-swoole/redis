<?php


namespace EasySwoole\Redis;


class ClusterClient extends Client
{
    /**
     * @var $isConnected bool
     */
    protected $isConnected=false;

    /**
     * @return bool
     */
    public function isConnected(): bool
    {
        return $this->isConnected;
    }

    /**
     * @param bool $isConnected
     */
    public function setIsConnected(bool $isConnected): void
    {
        $this->isConnected = $isConnected;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost(string $host): void
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


}