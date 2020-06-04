<?php


namespace EasySwoole\Redis;


class Client
{
    /**
     * @var \Swoole\Coroutine\Client
     */
    protected $client;
    protected $host;
    protected $port;
    protected $packageMaxLength;

    function __construct(string $host, int $port,$packageMaxLength=1024*1024*2)
    {
        $this->host = $host;
        $this->port = $port;
        $this->packageMaxLength = $packageMaxLength;
    }

    public function connect(float $timeout = 3.0): bool
    {
        if ($this->client == null) {
            $this->client = new \Swoole\Coroutine\Client(SWOOLE_TCP);
            $this->client->set([
                'open_eof_check'     => true,
                'package_eof'        => "\r\n",
                'package_max_length' =>  $this->packageMaxLength
            ]);
        }

        return $this->client->connect($this->host, $this->port, $timeout);
    }

    public function send(string $data): bool
    {
        return strlen($data) === $this->client->send($data);
    }

    public function sendCommand(array $commandList): bool
    {
        $argNum = count($commandList);
        $str = "*{$argNum}\r\n";
        foreach ($commandList as $value) {
            $len = strlen($value);
            $str = $str . '$' . "{$len}\r\n{$value}\r\n";
        }
        return $this->send($str);
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
            $result->setMsg($this->client->errMsg);
            return $result;
        }
        /**
         * 去除每行的\r\n
         */
        $str = substr($str, 0, -2);
        $op = substr($str, 0, 1);
        $result = $this->opHandel($op, $str, $timeout);
        return $result;
    }

    /**
     * 字符串处理方法
     * opHandel
     * @param $op
     * @param $value
     * @param $timeout
     * @return Response
     * @author Tioncico
     * Time: 11:52
     */
    protected function opHandel($op, $value, float $timeout)
    {
        $result = new Response();
        switch ($op) {
            case '+':
                {
                    $result = $this->successHandel($value);
                    break;
                }
            case '-':
                {
                    $result = $this->errorHandel($value);
                    break;
                }
            case ':':
                {
                    $result = $this->intHandel($value);
                    break;
                }
            case '$':
                {
                    $result = $this->batchHandel($value, $timeout);
                    break;
                }
            case "*":
                {
                    $result = $this->multipleBatchHandel($value, $timeout);
                    break;
                }
        }
        return $result;
    }

    /**
     * 状态类型处理
     * successHandel
     * @param $value
     * @return Response
     * @author Tioncico
     * Time: 11:52
     */
    protected function successHandel($value): Response
    {
        $result = new Response();
        $result->setStatus($result::STATUS_OK);
        $result->setData(substr($value, 1));
        return $result;
    }

    /**
     * 错误类型处理
     * errorHandel
     * @param $value
     * @return Response
     * @author Tioncico
     * Time: 11:53
     */
    protected function errorHandel($value): Response
    {
        $result = new Response();
        //查看空格位置
        $spaceIndex = strpos($value, ' ');
        //查看换行位置
        $lineIndex = strpos($value, PHP_EOL);
        if ($lineIndex === false || $lineIndex > $spaceIndex) {
            $result->setErrorType(substr($value, 1, $spaceIndex - 1));
        } else {
            $result->setErrorType(substr($value, 1, $lineIndex - 1));
        }
        $result->setStatus($result::STATUS_ERR);
        $result->setMsg(substr($value, 1));
        return $result;
    }

    /**
     * int类型处理
     * intHandel
     * @param $value
     * @return Response
     * @author Tioncico
     * Time: 11:53
     */
    protected function intHandel($value): Response
    {
        $result = new Response();
        $result->setStatus($result::STATUS_OK);
        $result->setData((int)substr($value, 1));
        return $result;
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
    protected function batchHandel($str, float $timeout)
    {
        $response = new Response();
        $strLen = substr($str, 1);
        //批量回复,继续读取字节
        $len = 0;
        $buff = '';
        if ($strLen == 0) {
            $this->client->recv($timeout);
            $response->setData('');
        } elseif ($strLen == -1) {
            $response->setData(null);
        } else {
            $eolLen = strlen("\r\n");
            while ($len < $strLen + $eolLen) {
                $strTmp = $this->client->recv($timeout);
                $len += strlen($strTmp);
                //超时
                if ($len < $strLen + $eolLen && empty($strTmp)) {
                    $response->setStatus($response::STATUS_TIMEOUT);
                    $response->setMsg($this->client->errMsg);
                    return $response;
                }
                $buff .= $strTmp;
            }
            $response->setData(substr($buff, 0, -2));
        }
        $response->setStatus($response::STATUS_OK);
        return $response;
    }

    /**
     * 多条批量回复
     * multipleBatchHandel
     * @param $value
     * @param $timeout
     * @return Response
     * @author Tioncico
     * Time: 14:33
     */
    protected function multipleBatchHandel($value, float $timeout)
    {
        $result = new Response();
        $len = substr($value, 1);
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
                if (empty($str)) {
                    $result->setStatus($result::STATUS_TIMEOUT);
                    $result->setMsg($this->client->errMsg);
                    return $result;
                }
                $str = substr($str, 0, -2);
                $op = substr($str, 0, 1);
                $response = $this->opHandel($op, $str, $timeout);
                if ($response->getStatus() != $response::STATUS_OK) {
                    $arr[] = false;
                } else {
                    $arr[] = $response->getData();
                }
            }
            $result->setStatus($result::STATUS_OK);
            $result->setData($arr);
        }
        return $result;
    }

    function close()
    {
        if ($this->client) {
            $this->client->close();
            $this->client = null;
        }
    }

    public function socketError()
    {
        return $this->client->errMsg;
    }

    public function socketErrno()
    {
        return $this->client->errCode;
    }

    public function __destruct()
    {
        $this->close();;
    }

}
