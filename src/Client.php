<?php


namespace EasySwoole\Redis;


class Client
{
    protected $client;
    protected $host;
    protected $port;

    function __construct(string $host, int $port)
    {
        $this->client = new \Swoole\Coroutine\Client(SWOOLE_TCP);
        $this->client->set([
            'open_eof_check' => true,
            'package_eof'    => "\r\n",
        ]);
        $this->host = $host;
        $this->port = $port;
    }

    public function connect(float $timeout = 3.0)
    {
        if ($this->client->isConnected()) {
            return true;
        }
        return $this->client->connect($this->host, $this->port, $timeout);
    }

    protected function send(string $data)
    {
        return $this->client->send($data);
    }

    function recv(float $timeout = 3.0): ?Response
    {
        /*
         *
            用单行回复，回复的第一个字节将是“+”
            错误消息，回复的第一个字节将是“-”
            整型数字，回复的第一个字节将是“:”
            批量回复，回复的第一个字节将是“$”
            多个批量回复，回复的第一个字节将是“*”
         */
        $result = new Response();
        $str = $this->client->recv($timeout);
        if (empty($str)) {
            $result->setStatus($result::STATUS_TIMEOUT);
            return $result;
        }
        /**
         * 去除每行的\r\n
         */
        $str = substr($str, 0, -2);
        $op = substr($str, 0, 1);
        switch ($op) {
            case '+':
                {
                    $result->setStatus($result::STATUS_OK);
                    $result->setData(substr($str, 1));
                    break;
                }
            case '-':
                {
                    //查看空格位置
                    $spaceIndex = strpos($str, ' ');
                    //查看换行位置
                    $lineIndex = strpos($str, PHP_EOL);
                    if ($lineIndex === false || $lineIndex > $spaceIndex) {
                        $result->setErrorType(substr($str, 1, $spaceIndex - 1));

                    } else {
                        $result->setErrorType(substr($str, 1, $lineIndex - 1));
                    }
                    $result->setStatus($result::STATUS_ERR);
                    $result->setMsg(substr($str, 1));
                    break;
                }
            case ':':
                {
                    $result->setStatus($result::STATUS_OK);
                    $result->setData(substr($str, 1));
                    break;
                }
            case '$':
                {
                    /**
                     * 批量回复处理
                     */
                    $str = $this->batchHandel($str, $timeout);
                    $result->setStatus($result::STATUS_OK);
                    $result->setData($str);
                    break;
                }
            case "*":
                {
                    $len = substr($str, 1);
                    if ($len == 0) {
                        $result->setStatus($result::STATUS_OK);
                        $result->setData([]);
                    } elseif ($len == -1) {
                        $result->setStatus($result::STATUS_OK);
                        $result->setData(null);
                    } else {
                        $arr = [];
                        while ($len--) {
                            $str = $this->client->recv($timeout);
                            $str = substr($str, 0, -2);
                            $op = substr($str, 0, 1);
                            switch ($op) {
                                case '+':
                                case ':':
                                    break;
                                case '$':
                                    if ($str == -1) {
                                        $str = null;
                                    }else {
                                        $str = $this->batchHandel($str, $timeout);
                                        break;
                                    }
                            }
                            $arr[] = $str;
                        }
                        $result->setStatus($result::STATUS_OK);
                        $result->setData($arr);
                    }
                    break;
                }
        }
        return $result;
    }

    public function sendCommand(array $commandList)
    {
        $argNum = count($commandList);
        $str = "*{$argNum}\r\n";
        foreach ($commandList as $value) {
            $len = strlen($value);
            $str = $str . '$' . "{$len}\r\n{$value}\r\n";
        }
        return $this->send($str);
    }


    /**
     * 批量回复处理
     * batchHandel
     * @param $str
     * @param $timeout
     * @return bool|string
     * @author Tioncico
     * Time: 17:13
     */
    protected function batchHandel($str, $timeout)
    {
        $strLen = substr($str, 1);
        //批量回复,继续读取字节
        $len = 0;
        $buff = '';
        while ($len < $strLen) {
            $strTmp = $this->client->recv($timeout);
            $len += strlen($strTmp);
            $buff .= $strTmp;
        }
        if (empty($buff)){
            return $buff;
        }else{
            return substr($buff, 0, -2);
        }
    }

}