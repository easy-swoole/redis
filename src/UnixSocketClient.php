<?php
/**
 * Created by PhpStorm.
 * User: tioncico
 * Date: 20-5-30
 * Time: 下午9:54
 */

namespace EasySwoole\Redis;


class UnixSocketClient extends Client
{
    /**
     * @var \Swoole\Coroutine\Client
     */
    protected $client;
    protected $unixSocket;
    protected $packageMaxLength;

    public function __construct($unixSocket,$packageMaxLength=1024*1024*2)
    {
        $this->unixSocket = $unixSocket;
        $this->packageMaxLength = $packageMaxLength;
    }

    public function connect(float $timeout = 3.0): bool
    {
        if ($this->client == null) {
            $this->client = new \Swoole\Coroutine\Client(SWOOLE_UNIX_STREAM);
            $this->client->set([
                'open_eof_check' => true,
                'package_eof'    => "\r\n",
                'package_max_length' =>  $this->packageMaxLength
            ]);
        }

        return $this->client->connect($this->unixSocket, null, $timeout);
    }
}