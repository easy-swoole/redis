<?php


namespace EasySwoole\Redis;


class Client
{
    protected $client;
    protected $host;
    protected $port;
    protected $timeout = 3.0;

    function __construct(string $host, int $port, float $timeout = 3.0)
    {
        $this->client = new \Swoole\Coroutine\Client(SWOOLE_TCP);
        $this->client->set([
            'open_eof_check' => true,
            'package_eof'    => "\r\n",
        ]);
        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;
    }

    public function connect()
    {
        if ($this->client->isConnected()) {
            return true;
        }
        return $this->client->connect($this->host, $this->port, $this->timeout);
    }

    protected function send(string $data)
    {
        return $this->client->send($data);
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

    function recv(): ?Response
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
        $str = $this->client->recv($this->timeout);
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
        $result = $this->opHandel($op, $str);
        return $result;
    }

    /**
     * 字符串处理方法
     * opHandel
     * @param $op
     * @param $value
     * @return Response
     * @author Tioncico
     * Time: 11:52
     */
    protected function opHandel($op, $value)
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
                    $result = $this->batchHandel($value);
                    break;
                }
            case "*":
                {
                    $result = $this->multipleBatchHandel($value);
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
    protected function batchHandel($str)
    {
        $response = new Response();
        $strLen = substr($str, 1);
        //批量回复,继续读取字节
        $len = 0;
        $buff = '';
        if ($strLen == 0) {
            $this->client->recv($this->timeout);
            $response->setData('');
        } else {
            while ($len < $strLen) {
                $strTmp = $this->client->recv($this->timeout);
                $len += strlen($strTmp);
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
     * @return Response
     * @author Tioncico
     * Time: 14:33
     */
    protected function multipleBatchHandel($value)
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
                $str = $this->client->recv($this->timeout);
                $str = substr($str, 0, -2);
                $op = substr($str, 0, 1);
                $response = $this->opHandel($op, $str);
                $arr[] = $response->getData();
            }
            $result->setStatus($result::STATUS_OK);
            $result->setData($arr);
        }
        return $result;
    }

}