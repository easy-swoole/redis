<?php


namespace EasySwoole\Redis;


class Response
{
    const STATUS_OK = 0;
    const STATUS_ERR = -1;
    const STATUS_TIMEOUT = -2;
    protected $status;
    protected $data;
    protected $msg;
    protected $errorType;

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data): void
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getMsg()
    {
        return $this->msg;
    }

    /**
     * @param mixed $msg
     */
    public function setMsg($msg): void
    {
        $this->msg = $msg;
    }

    /**
     * @return mixed
     */
    public function getErrorType()
    {
        return $this->errorType;
    }

    /**
     * @param mixed $errorType
     */
    public function setErrorType($errorType): void
    {
        $this->errorType = $errorType;
    }
}