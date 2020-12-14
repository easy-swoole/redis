<?php


namespace EasySwoole\Redis;


use EasySwoole\Redis\CommandHandel\AppEnd;
use EasySwoole\Redis\CommandHandel\Auth;
use EasySwoole\Redis\CommandHandel\BgRewriteAof;
use EasySwoole\Redis\CommandHandel\BgSave;
use EasySwoole\Redis\CommandHandel\BitCount;
use EasySwoole\Redis\CommandHandel\BitField;
use EasySwoole\Redis\CommandHandel\BitOp;
use EasySwoole\Redis\CommandHandel\BitPos;
use EasySwoole\Redis\CommandHandel\BLPop;
use EasySwoole\Redis\CommandHandel\BRPop;
use EasySwoole\Redis\CommandHandel\BRPopLPush;
use EasySwoole\Redis\CommandHandel\BZPopMax;
use EasySwoole\Redis\CommandHandel\BZPopMin;
use EasySwoole\Redis\CommandHandel\ClientGetName;
use EasySwoole\Redis\CommandHandel\ClientKill;
use EasySwoole\Redis\CommandHandel\ClientList;
use EasySwoole\Redis\CommandHandel\ClientPause;
use EasySwoole\Redis\CommandHandel\ClientSetName;
use EasySwoole\Redis\CommandHandel\CommandCount;
use EasySwoole\Redis\CommandHandel\Decr;
use EasySwoole\Redis\CommandHandel\DecrBy;
use EasySwoole\Redis\CommandHandel\Del;
use EasySwoole\Redis\CommandHandel\Discard;
use EasySwoole\Redis\CommandHandel\DiscardPipe;
use EasySwoole\Redis\CommandHandel\Dump;
use EasySwoole\Redis\CommandHandel\Exec;
use EasySwoole\Redis\CommandHandel\ExecPipe;
use EasySwoole\Redis\CommandHandel\Exists;
use EasySwoole\Redis\CommandHandel\Expire;
use EasySwoole\Redis\CommandHandel\ExpireAt;
use EasySwoole\Redis\CommandHandel\Get;
use EasySwoole\Redis\CommandHandel\GetBit;
use EasySwoole\Redis\CommandHandel\GetRange;
use EasySwoole\Redis\CommandHandel\GetSet;
use EasySwoole\Redis\CommandHandel\HDel;
use EasySwoole\Redis\CommandHandel\HExists;
use EasySwoole\Redis\CommandHandel\HGet;
use EasySwoole\Redis\CommandHandel\HGetAll;
use EasySwoole\Redis\CommandHandel\HIncrBy;
use EasySwoole\Redis\CommandHandel\HIncrByFloat;
use EasySwoole\Redis\CommandHandel\HKeys;
use EasySwoole\Redis\CommandHandel\HLen;
use EasySwoole\Redis\CommandHandel\HMGet;
use EasySwoole\Redis\CommandHandel\HMSet;
use EasySwoole\Redis\CommandHandel\HScan;
use EasySwoole\Redis\CommandHandel\HSet;
use EasySwoole\Redis\CommandHandel\HSetNx;
use EasySwoole\Redis\CommandHandel\HValS;
use EasySwoole\Redis\CommandHandel\Incr;
use EasySwoole\Redis\CommandHandel\IncrBy;
use EasySwoole\Redis\CommandHandel\IncrByFloat;
use EasySwoole\Redis\CommandHandel\Keys;
use EasySwoole\Redis\CommandHandel\LIndex;
use EasySwoole\Redis\CommandHandel\LInsert;
use EasySwoole\Redis\CommandHandel\LLen;
use EasySwoole\Redis\CommandHandel\LPop;
use EasySwoole\Redis\CommandHandel\LPush;
use EasySwoole\Redis\CommandHandel\LPuShx;
use EasySwoole\Redis\CommandHandel\LRange;
use EasySwoole\Redis\CommandHandel\LRem;
use EasySwoole\Redis\CommandHandel\LSet;
use EasySwoole\Redis\CommandHandel\LTrim;
use EasySwoole\Redis\CommandHandel\MGet;
use EasySwoole\Redis\CommandHandel\Move;
use EasySwoole\Redis\CommandHandel\MSet;
use EasySwoole\Redis\CommandHandel\MSetNx;
use EasySwoole\Redis\CommandHandel\Multi;
use EasySwoole\Redis\CommandHandel\Persist;
use EasySwoole\Redis\CommandHandel\PExpire;
use EasySwoole\Redis\CommandHandel\PfAdd;
use EasySwoole\Redis\CommandHandel\PfCount;
use EasySwoole\Redis\CommandHandel\PfMerge;
use EasySwoole\Redis\CommandHandel\Ping;
use EasySwoole\Redis\CommandHandel\PSetEx;
use EasySwoole\Redis\CommandHandel\PSubscribe;
use EasySwoole\Redis\CommandHandel\PTTL;
use EasySwoole\Redis\CommandHandel\Publish;
use EasySwoole\Redis\CommandHandel\PubSub;
use EasySwoole\Redis\CommandHandel\PUnSubscribe;
use EasySwoole\Redis\CommandHandel\RandomKey;
use EasySwoole\Redis\CommandHandel\Rename;
use EasySwoole\Redis\CommandHandel\RenameNx;
use EasySwoole\Redis\CommandHandel\RPop;
use EasySwoole\Redis\CommandHandel\RPopLPush;
use EasySwoole\Redis\CommandHandel\RPush;
use EasySwoole\Redis\CommandHandel\RPuShx;
use EasySwoole\Redis\CommandHandel\SAdd;
use EasySwoole\Redis\CommandHandel\Scan;
use EasySwoole\Redis\CommandHandel\SCard;
use EasySwoole\Redis\CommandHandel\SDiff;
use EasySwoole\Redis\CommandHandel\SDiffStore;
use EasySwoole\Redis\CommandHandel\Select;
use EasySwoole\Redis\CommandHandel\Set;
use EasySwoole\Redis\CommandHandel\SetBit;
use EasySwoole\Redis\CommandHandel\SetEx;
use EasySwoole\Redis\CommandHandel\SetNx;
use EasySwoole\Redis\CommandHandel\SetRange;
use EasySwoole\Redis\CommandHandel\SInter;
use EasySwoole\Redis\CommandHandel\SInterStore;
use EasySwoole\Redis\CommandHandel\SIsMember;
use EasySwoole\Redis\CommandHandel\SMembers;
use EasySwoole\Redis\CommandHandel\SMove;
use EasySwoole\Redis\CommandHandel\SPop;
use EasySwoole\Redis\CommandHandel\SRandMember;
use EasySwoole\Redis\CommandHandel\SRem;
use EasySwoole\Redis\CommandHandel\SScan;
use EasySwoole\Redis\CommandHandel\StartPipe;
use EasySwoole\Redis\CommandHandel\XAck;
use EasySwoole\Redis\CommandHandel\XAdd;
use EasySwoole\Redis\CommandHandel\XClaim;
use EasySwoole\Redis\CommandHandel\XDel;
use EasySwoole\Redis\CommandHandel\XGroup;
use EasySwoole\Redis\CommandHandel\XInfo;
use EasySwoole\Redis\CommandHandel\XLen;
use EasySwoole\Redis\CommandHandel\XPending;
use EasySwoole\Redis\CommandHandel\XRange;
use EasySwoole\Redis\CommandHandel\XRead;
use EasySwoole\Redis\CommandHandel\XReadGroup;
use EasySwoole\Redis\CommandHandel\XRevRange;
use EasySwoole\Redis\CommandHandel\XTrim;
use EasySwoole\Redis\CommandHandel\StrLen;
use EasySwoole\Redis\CommandHandel\Subscribe;
use EasySwoole\Redis\CommandHandel\SUnion;
use EasySwoole\Redis\CommandHandel\SUnIonStore;
use EasySwoole\Redis\CommandHandel\Ttl;
use EasySwoole\Redis\CommandHandel\Type;
use EasySwoole\Redis\CommandHandel\Unlink;
use EasySwoole\Redis\CommandHandel\Unsubscribe;
use EasySwoole\Redis\CommandHandel\UnWatch;
use EasySwoole\Redis\CommandHandel\Watch;
use EasySwoole\Redis\CommandHandel\ZAdd;
use EasySwoole\Redis\CommandHandel\ZCard;
use EasySwoole\Redis\CommandHandel\ZCount;
use EasySwoole\Redis\CommandHandel\ZInCrBy;
use EasySwoole\Redis\CommandHandel\ZInTerStore;
use EasySwoole\Redis\CommandHandel\ZLexCount;
use EasySwoole\Redis\CommandHandel\ZPopMax;
use EasySwoole\Redis\CommandHandel\ZPopMin;
use EasySwoole\Redis\CommandHandel\ZRange;
use EasySwoole\Redis\CommandHandel\ZRangeByLex;
use EasySwoole\Redis\CommandHandel\ZRangeByScore;
use EasySwoole\Redis\CommandHandel\ZRank;
use EasySwoole\Redis\CommandHandel\ZRem;
use EasySwoole\Redis\CommandHandel\ZRemRangeByLex;
use EasySwoole\Redis\CommandHandel\ZRemRangeByRank;
use EasySwoole\Redis\CommandHandel\ZRemRangeByScore;
use EasySwoole\Redis\CommandHandel\ZRevRange;
use EasySwoole\Redis\CommandHandel\ZRevRangeByScore;
use EasySwoole\Redis\CommandHandel\ZRevRank;
use EasySwoole\Redis\CommandHandel\ZScan;
use EasySwoole\Redis\CommandHandel\ZScore;
use EasySwoole\Redis\CommandHandel\ZUnionStore;
use EasySwoole\Redis\CommandHandel\CommandGetKeys;
use EasySwoole\Redis\CommandHandel\Time;
use EasySwoole\Redis\CommandHandel\CommandInfo;
use EasySwoole\Redis\CommandHandel\ConfigGet;
use EasySwoole\Redis\CommandHandel\ConfigRewrite;
use EasySwoole\Redis\CommandHandel\ConfigSet;
use EasySwoole\Redis\CommandHandel\ConfigResetStat;
use EasySwoole\Redis\CommandHandel\DBSize;
use EasySwoole\Redis\CommandHandel\DebugObject;
use EasySwoole\Redis\CommandHandel\DebugSegfault;
use EasySwoole\Redis\CommandHandel\FlushAll;
use EasySwoole\Redis\CommandHandel\FlushDb;
use EasySwoole\Redis\CommandHandel\Info;
use EasySwoole\Redis\CommandHandel\LastSave;
use EasySwoole\Redis\CommandHandel\Monitor;
use EasySwoole\Redis\CommandHandel\Role;
use EasySwoole\Redis\CommandHandel\Save;
use EasySwoole\Redis\CommandHandel\Shutdown;
use EasySwoole\Redis\CommandHandel\SlowLog;
use EasySwoole\Redis\CommandHandel\SYNC;
use EasySwoole\Redis\CommandHandel\GeoAdd;
use EasySwoole\Redis\CommandHandel\GeoDist;
use EasySwoole\Redis\CommandHandel\GeoHash;
use EasySwoole\Redis\CommandHandel\GeoPos;
use EasySwoole\Redis\CommandHandel\GeoRadius;
use EasySwoole\Redis\CommandHandel\GeoRadiusByMember;
use EasySwoole\Redis\Config\RedisConfig;
use EasySwoole\Redis\Exception\RedisException;
use EasySwoole\Redis\CommandConst as Command;

class Redis
{
    protected $config;
    /** @var Client */
    protected $client;
    protected $isConnected;
    protected $tryConnectTimes = 0;
    protected $lastSocketError;
    protected $lastSocketErrno;
    protected $errorType;
    protected $errorMsg;

    /**
     * @var bool 是否停止消息订阅
     */
    protected $subscribeStop = true;

    /**
     * @var bool 是否停止命令监听
     */
    protected $monitorStop = true;

    /**
     * @var null 事务
     */
    protected $transaction = null;

    /**
     * @var null 管道
     */
    protected $pipe = null;

    public function __construct(RedisConfig $config)
    {
        $this->config = $config;
    }

    ######################服务器连接方法######################

    public function connect(float $timeout = null): bool
    {
        if ($this->isConnected) {
            return true;
        }
        if ($timeout === null) {
            $timeout = $this->config->getTimeout();
        }
        if ($this->client == null) {
            $this->initClient();
        }
        $this->isConnected = $this->client->connect($timeout);

        if ($this->isConnected && !empty($this->config->getAuth())) {
            if (!$this->auth($this->config->getAuth())) {
                $this->isConnected = false;
                throw new RedisException("auth to redis host {$this->config->getHost()}:{$this->config->getPort()} fail");
            }
        }
        if ($this->isConnected && $this->config->getDb() !== null) {
            $this->select($this->config->getDb());
        }

        return $this->isConnected;
    }

    function initClient()
    {
        if ($this->config->getUnixSocket() !== null) {
            $this->client = new UnixSocketClient($this->config->getUnixSocket(),$this->config->getPackageMaxLength());
        } else {
            $this->client = new Client($this->config->getHost(), $this->config->getPort(),$this->config->getPackageMaxLength());
        }
    }

    function disconnect()
    {
        if ($this->isConnected) {
            $this->isConnected = false;
            $this->lastSocketError = $this->client->socketError();
            $this->lastSocketErrno = $this->client->socketErrno();
            $this->client->close();
        }
    }

    protected function reset()
    {
        $this->tryConnectTimes = 0;
        $this->lastSocketErrno = 0;
        $this->lastSocketError = '';
        $this->subscribeStop = true;
        $this->monitorStop = true;
        $this->errorMsg = '';
        $this->errorType = '';
    }

    public function auth($password): bool
    {
        $handelClass = new Auth($this);
        $command = $handelClass->getCommand($password);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function echo($str)
    {
        $data = [Command::ECHO, $str];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function ping()
    {
        $handelClass = new Ping($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function select($db): bool
    {
        $handelClass = new Select($this);
        $command = $handelClass->getCommand($db);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    ######################服务器连接方法######################

    ######################key操作方法######################

    public function del(...$keys)
    {
        $handelClass = new Del($this);
        $command = $handelClass->getCommand(...$keys);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }


    public function unlink(...$keys)
    {
        $handelClass = new Unlink($this);
        $command = $handelClass->getCommand(...$keys);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function dump($key)
    {
        $handelClass = new Dump($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function exists($key)
    {
        $handelClass = new Exists($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function expire($key, $expireTime = 60)
    {
        $handelClass = new Expire($this);
        $command = $handelClass->getCommand($key, $expireTime);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function pExpire($key, $expireTime = 60000)
    {
        $handelClass = new PExpire($this);
        $command = $handelClass->getCommand($key, $expireTime);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }
    
    public function expireAt($key, $expireTime)
    {
        $handelClass = new ExpireAt($this);
        $command = $handelClass->getCommand($key, $expireTime);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function keys($pattern)
    {
        $handelClass = new Keys($this);
        $command = $handelClass->getCommand($pattern);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function move($key, $db)
    {
        $handelClass = new Move($this);
        $command = $handelClass->getCommand($key, $db);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function persist($key)
    {
        $handelClass = new Persist($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function pTTL($key)
    {
        $handelClass = new PTTL($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function ttl($key)
    {
        $handelClass = new Ttl($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function randomKey()
    {
        $handelClass = new RandomKey($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function rename($key, $new_key): bool
    {
        $handelClass = new Rename($this);
        $command = $handelClass->getCommand($key, $new_key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function renameNx($key, $new_key)
    {
        $handelClass = new RenameNx($this);
        $command = $handelClass->getCommand($key, $new_key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function type($key)
    {
        $handelClass = new Type($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    ######################key操作方法######################


    ######################字符串方法######################

    /**
     * set
     * @param                  $key
     * @param                  $val
     * @param int|string|array $timeout $timeout [optional]
     * $timeout = 10
     * $timeout = 'XX',timeout='NX'
     * ['NX','EX'=>10],['XX','PX'=>10]
     * @return bool
     * @throws RedisException
     * @author Tioncico
     * Time: 14:33
     */
    public function set($key, $val, $timeout = 0): ?bool
    {
        $handelClass = new Set($this);
        $command = $handelClass->getCommand($key, $val, $timeout);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function get($key)
    {
        $handelClass = new Get($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function getRange($key, $start, $end)
    {
        $handelClass = new GetRange($this);
        $command = $handelClass->getCommand($key, $start, $end);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function getSet($key, $value)
    {
        $handelClass = new GetSet($this);
        $command = $handelClass->getCommand($key, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function mGet($keys)
    {
        $handelClass = new MGet($this);
        $command = $handelClass->getCommand($keys);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function setEx($key, $expireTime, $value): bool
    {
        $handelClass = new SetEx($this);
        $command = $handelClass->getCommand($key, $expireTime, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function setNx($key, $value)
    {
        $handelClass = new SetNx($this);
        $command = $handelClass->getCommand($key, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function setRange($key, $offset, $value)
    {
        $handelClass = new SetRange($this);
        $command = $handelClass->getCommand($key, $offset, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function strLen($key)
    {
        $handelClass = new StrLen($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function mSet($data): bool
    {
        $handelClass = new MSet($this);

        $command = $handelClass->getCommand($data);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function mSetNx($data)
    {
        $handelClass = new MSetNx($this);
        $command = $handelClass->getCommand($data);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function pSetEx($key, $expireTime, $value)
    {
        $handelClass = new PSetEx($this);
        $command = $handelClass->getCommand($key, $expireTime, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function incr($key)
    {
        $handelClass = new Incr($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function incrBy($key, $value)
    {
        $handelClass = new IncrBy($this);
        $command = $handelClass->getCommand($key, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function incrByFloat($key, $value)
    {
        $handelClass = new IncrByFloat($this);
        $command = $handelClass->getCommand($key, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function decr($key)
    {
        $handelClass = new Decr($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function decrBy($key, $value)
    {
        $handelClass = new DecrBy($this);
        $command = $handelClass->getCommand($key, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function appEnd($key, $value)
    {
        $handelClass = new AppEnd($this);
        $command = $handelClass->getCommand($key, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function scan(&$cursor, $pattern = null, $count = null)
    {
        $handelClass = new Scan($this);
        $command = $handelClass->getCommand($cursor, $pattern, $count);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        $data = $handelClass->getData($recv);
        $cursor = $data[0];

        return $data[1];
    }

    ######################字符串方法######################


    ######################hash操作方法####################

    public function hDel($key, ...$field)
    {
        $handelClass = new HDel($this);
        $command = $handelClass->getCommand($key, ...$field);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function hExists($key, $field)
    {
        $handelClass = new HExists($this);
        $command = $handelClass->getCommand($key, $field);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function hGet($key, $field)
    {
        $handelClass = new HGet($this);
        $command = $handelClass->getCommand($key, $field);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function hGetAll($key)
    {
        $handelClass = new HGetAll($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function hSet($key, $field, $value)
    {
        $handelClass = new HSet($this);
        $command = $handelClass->getCommand($key, $field, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function hValS($key)
    {
        $handelClass = new HValS($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function hKeys($key)
    {
        $handelClass = new HKeys($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function hLen($key)
    {
        $handelClass = new HLen($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function hMGet($key, array $hashKeys)
    {
        $handelClass = new HMGet($this);
        $command = $handelClass->getCommand($key, $hashKeys);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function hMSet($key, $data): bool
    {
        $handelClass = new HMSet($this);
        $command = $handelClass->getCommand($key, $data);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function hIncrBy($key, $field, $increment)
    {
        $handelClass = new HIncrBy($this);
        $command = $handelClass->getCommand($key, $field, $increment);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function hIncrByFloat($key, $field, $increment)
    {
        $handelClass = new HIncrByFloat($this);
        $command = $handelClass->getCommand($key, $field, $increment);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function hSetNx($key, $field, $value)
    {
        $handelClass = new HSetNx($this);
        $command = $handelClass->getCommand($key, $field, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function hScan($key, &$cursor, $pattern = null, $count = null)
    {
        $handelClass = new HScan($this);
        $command = $handelClass->getCommand($key, $cursor, $pattern, $count);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        $data = $handelClass->getData($recv);
        $cursor = $data[0];
        return $data[1];
    }

    ######################hash操作方法######################

    ######################列表操作方法######################

    public function bLPop($keys, $timeout)
    {
        $handelClass = new BLPop($this);
        $command = $handelClass->getCommand($keys, $timeout);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function bRPop($keys, $timeout)
    {
        $handelClass = new BRPop($this);
        $command = $handelClass->getCommand($keys, $timeout);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function bRPopLPush($source, $destination, $timeout)
    {
        $handelClass = new BRPopLPush($this);
        $command = $handelClass->getCommand($source, $destination, $timeout);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function lIndex($key, $index)
    {
        $handelClass = new LIndex($this);
        $command = $handelClass->getCommand($key, $index);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function lInsert($key, bool $isBefore, $pivot, $value)
    {
        $handelClass = new LInsert($this);
        $command = $handelClass->getCommand($key, $isBefore == true ? 'BEFORE' : 'AFTER', $pivot, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function lLen($key)
    {
        $handelClass = new LLen($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function lPop($key)
    {
        $handelClass = new LPop($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function lPush($key, ...$data)
    {
        $handelClass = new LPush($this);
        $command = $handelClass->getCommand($key, ...$data);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function lPuShx($key, $value)
    {
        $handelClass = new LPuShx($this);
        $command = $handelClass->getCommand($key, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function lRange($key, $start, $stop)
    {
        $handelClass = new LRange($this);
        $command = $handelClass->getCommand($key, $start, $stop);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function lRem($key, $count, $value)
    {
        $handelClass = new LRem($this);
        $command = $handelClass->getCommand($key, $count, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function lSet($key, $index, $value): bool
    {
        $handelClass = new LSet($this);
        $command = $handelClass->getCommand($key, $index, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function lTrim($key, $start, $stop): bool
    {
        $handelClass = new LTrim($this);
        $command = $handelClass->getCommand($key, $start, $stop);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function rPop($key)
    {
        $handelClass = new RPop($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function rPopLPush($source, $destination)
    {
        $handelClass = new RPopLPush($this);
        $command = $handelClass->getCommand($source, $destination);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function rPush($key, ...$data)
    {
        $handelClass = new RPush($this);
        $command = $handelClass->getCommand($key, ...$data);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function rPuShx($key, $value)
    {
        $handelClass = new RPuShx($this);
        $command = $handelClass->getCommand($key, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }
    ######################列表操作方法######################

    ######################集合操作方法######################
    public function sAdd($key, ...$data)
    {
        $handelClass = new SAdd($this);
        $command = $handelClass->getCommand($key, $data);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function sCard($key)
    {
        $handelClass = new SCard($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function sDiff($key1, ...$keys)
    {
        $handelClass = new SDiff($this);
        $command = $handelClass->getCommand($key1, ...$keys);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function sDiffStore($destination, ...$keys)
    {
        $handelClass = new SDiffStore($this);
        $command = $handelClass->getCommand($destination, ...$keys);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function sInter($key1, ...$keys)
    {
        $handelClass = new SInter($this);
        $command = $handelClass->getCommand($key1, ...$keys);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function sInterStore($destination, ...$keys)
    {
        $handelClass = new SInterStore($this);
        $command = $handelClass->getCommand($destination, ...$keys);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function sIsMember($key, $member)
    {
        $handelClass = new SIsMember($this);
        $command = $handelClass->getCommand($key, $member);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function sMembers($key)
    {
        $handelClass = new SMembers($this);
        $command = $handelClass->getCommand($key);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function sMove($source, $destination, $member)
    {
        $handelClass = new SMove($this);
        $command = $handelClass->getCommand($source, $destination, $member);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function sPop($key, $count = 1)
    {
        $handelClass = new SPop($this);
        $command = $handelClass->getCommand($key, $count);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function sRandMember($key, $count = null)
    {
        $handelClass = new SRandMember($this);
        $command = $handelClass->getCommand($key, $count);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function sRem($key, $member1, ...$members)
    {
        $handelClass = new SRem($this);
        $command = $handelClass->getCommand($key, $member1, ...$members);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function sUnion($key1, ...$keys)
    {
        $handelClass = new SUnion($this);
        $command = $handelClass->getCommand($key1, ...$keys);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function sUnIonStore($destination, $key1, ...$keys)
    {
        $handelClass = new SUnIonStore($this);
        $command = $handelClass->getCommand($destination, $key1, ...$keys);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function sScan($key, &$cursor, $pattern = null, $count = null)
    {
        $handelClass = new SScan($this);
        $command = $handelClass->getCommand($key, $cursor, $pattern, $count);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        $data = $handelClass->getData($recv);
        $cursor = $data[0];
        return $data[1];
    }


    ######################集合操作方法######################

    ######################有序集合操作方法######################
    public function zAdd($key, $score1, $member1, ...$data)
    {
        $handelClass = new ZAdd($this);
        $command = $handelClass->getCommand($key, $score1, $member1, ...$data);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zCard($key)
    {
        $handelClass = new ZCard($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zCount($key, $min, $max)
    {
        $handelClass = new ZCount($this);
        $command = $handelClass->getCommand($key, $min, $max);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zInCrBy($key, $increment, $member)
    {
        $handelClass = new ZInCrBy($this);
        $command = $handelClass->getCommand($key, $increment, $member);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zInTerStore($destination, array $keys, array $weights = [], $aggregate = 'SUM')
    {
        $handelClass = new ZInTerStore($this);
        $command = $handelClass->getCommand($destination, $keys, $weights, $aggregate);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zLexCount($key, $min, $max)
    {
        $handelClass = new ZLexCount($this);
        $command = $handelClass->getCommand($key, $min, $max);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }
    
    public function zPopMax($key, $count = 1)
    {
        $handelClass = new ZPopMax($this);
        $command = $handelClass->getCommand($key, $count);
        
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }
    
    public function zPopMin($key, $count = 1)
    {
        $handelClass = new ZPopMin($this);
        $command = $handelClass->getCommand($key, $count);
        
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }
    
    public function zRange($key, $start, $stop, $withScores = false)
    {
        $handelClass = new ZRange($this);
        $command = $handelClass->getCommand($key, $start, $stop, $withScores);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zRangeByLex($key, $min, $max, ...$data)
    {
        $handelClass = new ZRangeByLex($this);
        $command = $handelClass->getCommand($key, $min, $max, ...$data);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    /**
     * zRangeByScore
     * @param         $key
     * @param         $min
     * @param         $max
     * @param   array $options Two options are available:
     *                          - withScores => TRUE,
     *                          - and limit => array($offset, $count)
     * @return bool|string
     * @throws RedisException
     * @author Tioncico
     * Time: 9:38
     */
    public function zRangeByScore($key, $min, $max, array $options)
    {
        $handelClass = new ZRangeByScore($this);
        $command = $handelClass->getCommand($key, $min, $max, $options);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zRank($key, $member)
    {
        $handelClass = new ZRank($this);
        $command = $handelClass->getCommand($key, $member);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zRem($key, $member, ...$members)
    {
        $handelClass = new ZRem($this);
        $command = $handelClass->getCommand($key, $member, ...$members);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zRemRangeByLex($key, $min, $max)
    {
        $handelClass = new ZRemRangeByLex($this);
        $command = $handelClass->getCommand($key, $min, $max);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zRemRangeByRank($key, $start, $stop)
    {
        $handelClass = new ZRemRangeByRank($this);
        $command = $handelClass->getCommand($key, $start, $stop);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zRemRangeByScore($key, $min, $max)
    {
        $handelClass = new ZRemRangeByScore($this);
        $command = $handelClass->getCommand($key, $min, $max);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zRevRange($key, $start, $stop, $withScores = false)
    {
        $handelClass = new ZRevRange($this);
        $command = $handelClass->getCommand($key, $start, $stop, $withScores);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zRevRangeByScore($key, $max, $min, array $options)
    {
        $handelClass = new ZRevRangeByScore($this);
        $command = $handelClass->getCommand($key, $max, $min, $options);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zRevRank($key, $member)
    {
        $handelClass = new ZRevRank($this);
        $command = $handelClass->getCommand($key, $member);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zScore($key, $member)
    {
        $handelClass = new ZScore($this);
        $command = $handelClass->getCommand($key, $member);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        $result = $handelClass->getData($recv);
        return $result;
    }

    public function zUnionStore($destination, array $keys, array $weights = [], $aggregate = 'SUM')
    {
        $handelClass = new ZUnionStore($this);
        $command = $handelClass->getCommand($destination, $keys, $weights, $aggregate);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function zScan($key, &$cursor, $pattern = null, $count = null)
    {
        $handelClass = new ZScan($this);
        $command = $handelClass->getCommand($key, $cursor, $pattern, $count);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        $data = $handelClass->getData($recv);
        $cursor = $data[0];
        return $data[1];
    }

    public function bZPopMax($key, $timeout)
    {
        $handelClass = new BZPopMax($this);
        $command = $handelClass->getCommand($key, $timeout);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv(($timeout == 0) ? -1 : $timeout + 1);
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function bZPopMin($key, $timeout)
    {
        $handelClass = new BZPopMin($this);
        $command = $handelClass->getCommand($key, $timeout);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv(($timeout == 0) ? -1 : $timeout + 1);
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    ######################有序集合操作方法######################

    ######################Stream操作方法######################

    public function xAdd(string $key, string $id, array $messages, $maxLen = null, bool $isApproximate = false)
    {
        $handelClass = new XAdd($this);
        $command = $handelClass->getCommand($key, $id, $messages, $maxLen, $isApproximate);
        if (!$this->sendCommand($command)){
            return false;
        }
        $recv = $this->recv();
        if ($recv == null) {
            return false;
        }
        $data = $handelClass->getData($recv);
        return $data;
    }

    public function xLen(string $key)
    {
        $handelClass = new XLen($this);
        $command = $handelClass->getCommand($key);
        if (!$this->sendCommand($command)){
            return false;
        }
        $recv = $this->recv();
        if ($recv == null) {
            return false;
        }
        $data = $handelClass->getData($recv);
        return $data;
    }

    public function xDel(string $key, array $ids)
    {
        $handelClass = new XDel($this);
        $command = $handelClass->getCommand($key, $ids);
        if (!$this->sendCommand($command)){
            return false;
        }
        $recv = $this->recv();
        if ($recv == null) {
            return false;
        }
        $data = $handelClass->getData($recv);
        return $data;
    }

    public function xRange(string $key, string $start = '-', string $end = '+', $count = null)
    {
        $handelClass = new XRange($this);
        $command = $handelClass->getCommand($key, $start, $end, $count);
        if (!$this->sendCommand($command)){
            return false;
        }
        $recv = $this->recv();
        if ($recv == null) {
            return false;
        }
        $data = $handelClass->getData($recv);
        return $data;
    }

    public function xRevRange(string $key, string $end = '+', string $start = '-', $count = null)
    {
        $handelClass = new XRevRange($this);
        $command = $handelClass->getCommand($key, $end, $start, $count);
        if (!$this->sendCommand($command)){
            return false;
        }
        $recv = $this->recv();
        if ($recv == null) {
            return false;
        }
        $data = $handelClass->getData($recv);
        return $data;
    }

    public function xTrim(string $key, $maxLen = null, bool $isApproximate = false)
    {
        $handelClass = new XTrim($this);
        $command = $handelClass->getCommand($key, $maxLen, $isApproximate);
        if (!$this->sendCommand($command)){
            return false;
        }
        $recv = $this->recv();
        if ($recv == null) {
            return false;
        }
        $data = $handelClass->getData($recv);
        return $data;
    }

    public function xRead(array $streams, $count = null, $block = null)
    {
        $handelClass = new XRead($this);
        $command = $handelClass->getCommand($streams, $count, $block);
        if (!$this->sendCommand($command)){
            return false;
        }
        $recv = $this->recv(-1);
        if ($recv == null) {
            return false;
        }
        $data = $handelClass->getData($recv);
        return $data;
    }

    public function xReadGroup(string $group, string $consumer,array $streams, $count = null, $block = null)
    {
        $handelClass = new XReadGroup($this);
        $command = $handelClass->getCommand($group, $consumer, $streams, $count, $block);
        if (!$this->sendCommand($command)){
            return false;
        }
        $recv = $this->recv(-1);
        if ($recv == null) {
            return false;
        }
        $data = $handelClass->getData($recv);
        return $data;
    }

    public function xGroup(string $operation, string $key = '', string $group = '', string $msgId = '$', bool $mkStream = false)
    {
        $handelClass = new XGroup($this);
        $command = $handelClass->getCommand($operation, $key, $group, $msgId, $mkStream);
        if (!$this->sendCommand($command)){
            return false;
        }
        $recv = $this->recv();
        if ($recv == null) {
            return false;
        }
        $data = $handelClass->getData($recv);
        return $data;
    }

    public function xInfo(string $operation, string $key = '', string $group = '')
    {
        $handelClass = new XInfo($this);
        $command = $handelClass->getCommand($operation, $key, $group);
        if (!$this->sendCommand($command)){
            return false;
        }
        $recv = $this->recv();
        if ($recv == null) {
            return false;
        }
        $data = $handelClass->getData($recv);
        return $data;
    }

    public function xPending(string $stream, string $group, string $start = null, string $end = null, $count = null, $consumer = null)
    {
        $handelClass = new XPending($this);
        $command = $handelClass->getCommand($stream, $group, $start, $end, $count, $consumer);
        if (!$this->sendCommand($command)){
            return false;
        }
        $recv = $this->recv();
        if ($recv == null) {
            return false;
        }
        $data = $handelClass->getData($recv);
        return $data;
    }

    public function xAck(string $key, string $group, array $ids = [])
    {
        $handelClass = new XAck($this);
        $command = $handelClass->getCommand($key, $group, $ids);
        if (!$this->sendCommand($command)){
            return false;
        }
        $recv = $this->recv();
        if ($recv == null) {
            return false;
        }
        $data = $handelClass->getData($recv);
        return $data;
    }

    public function xClaim(string $key, string $group, string $consumer, int $minIdleTime, array $ids, array $options = [])
    {
        $handelClass = new XClaim($this);
        $command = $handelClass->getCommand($key, $group, $consumer, $minIdleTime, $ids, $options);
        if (!$this->sendCommand($command)){
            return false;
        }
        $recv = $this->recv();
        if ($recv == null) {
            return false;
        }
        $data = $handelClass->getData($recv);
        return $data;
    }

    ######################Stream操作方法######################


    ######################Bitmap操作方法######################

    public function setBit($key, $offset, $value)
    {
        $handelClass = new SetBit($this);
        $command = $handelClass->getCommand($key, $offset, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function getBit($key, $offset)
    {
        $handelClass = new GetBit($this);
        $command = $handelClass->getCommand($key, $offset);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function bitCount(string $key, ?int $start = null, ?int $end = null)
    {
        $handelClass = new BitCount($this);
        $command = $handelClass->getCommand($key, $start, $end);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function bitPos(string $key, int $bit, ?int $start = null, ?int $end = null)
    {
        $handelClass = new BitPos($this);
        $command = $handelClass->getCommand($key, $bit, $start, $end);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function bitOp(string $operation, string $destKey, string $key1, ...$otherKeys)
    {
        $handelClass = new BitOp($this);
        $command = $handelClass->getCommand($operation, $destKey, $key1, $otherKeys);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function bitField(string $key, array $subcommands = [], ?string $overflow = null, array $subcommandArgs = [])
    {
        $handelClass = new BitField($this);
        $command = $handelClass->getCommand($key, $subcommands, $overflow, $subcommandArgs);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    ######################Bitmap操作方法######################

    ######################Stream操作方法######################



    ######################HyperLogLog操作方法######################

    public function pfAdd($key, $elements)
    {
        $handelClass = new PfAdd($this);
        $command = $handelClass->getCommand($key, $elements);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function pfCount($key)
    {
        $handelClass = new PfCount($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function pfMerge($deStKey, array $sourceKeys)
    {
        $handelClass = new PfMerge($this);
        $command = $handelClass->getCommand($deStKey, $sourceKeys);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    ######################HyperLogLog操作方法######################

    ######################发布订阅操作方法######################

    public function pSubscribe($callback, $pattern, ...$patterns)
    {
        $handelClass = new PSubscribe($this);
        $command = $handelClass->getCommand($callback, $pattern, ...$patterns);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function pubSub($subCommand, ...$arguments)
    {
        $handelClass = new PubSub($this);
        $command = $handelClass->getCommand($subCommand, ...$arguments);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function publish($channel, $message)
    {
        $handelClass = new Publish($this);
        $command = $handelClass->getCommand($channel, $message);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function pUnSubscribe($pattern, ...$patterns)
    {
        $handelClass = new PUnSubscribe($this);
        $command = $handelClass->getCommand($pattern, ...$patterns);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function subscribe($callback, $channel, ...$channels)
    {
        $handelClass = new Subscribe($this);
        $command = $handelClass->getCommand($callback, $channel, ...$channels);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function unsubscribe($channel, ...$channels)
    {
        $handelClass = new Unsubscribe($this);
        $command = $handelClass->getCommand($channel, ...$channels);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    /**
     * @param bool $subscribeStop
     */
    public function setSubscribeStop(bool $subscribeStop): void
    {
        $this->subscribeStop = $subscribeStop;
    }

    /**
     * @return bool
     */
    public function isSubscribeStop(): bool
    {
        return $this->subscribeStop;
    }

    ######################发布订阅操作方法######################

    ######################事务操作方法)######################
    public function discard(): bool
    {
        $handelClass = new Discard($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function exec()
    {
        $handelClass = new Exec($this);
        $command = $handelClass->getCommand();
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function multi(): bool
    {
        $handelClass = new Multi($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function unWatch(): bool
    {
        $handelClass = new UnWatch($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function watch($key, ...$keys): bool
    {
        $handelClass = new Watch($this);
        $command = $handelClass->getCommand($key, ...$keys);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function getTransaction(): ?RedisTransaction
    {
        return $this->transaction;
    }

    public function setTransaction(?RedisTransaction $transaction): void
    {
        if (!$this->getTransaction() instanceof RedisTransaction) {
            $this->transaction = $transaction;
        }
    }

    ######################事务操作方法######################

    ######################管道操作方法######################
    public function discardPipe(): bool
    {
        $handelClass = new DiscardPipe($this);
        //模拟命令,不实际执行
        $handelClass->getCommand();
        //模拟获取服务器数据,不实际执行
        $recv = new Response();
        $recv->setStatus($recv::STATUS_OK);
        $recv->setData(true);
        return $handelClass->getData($recv);
    }

    public function execPipe()
    {
        $handelClass = new ExecPipe($this);
        $commandData = $handelClass->getCommand();
        //发送原始tcp数据
        if (!$this->client->send($commandData)) {
            return false;
        }
        //模拟获取服务器数据,不实际执行
        $recv = new Response();
        $recv->setStatus($recv::STATUS_OK);
        $recv->setData(true);
        return $handelClass->getData($recv);
    }

    public function startPipe(): bool
    {
        //由于执行管道之后,connect方法也会被拦截,导致没有client执行数据,所以这边先连接一次
        if ($this->connect() === false) {
            throw new RedisException("redis connect error");
        }
        $handelClass = new StartPipe($this);
        //模拟命令,不实际执行
        $handelClass->getCommand();
        //模拟获取服务器数据,不实际执行
        $recv = new Response();
        $recv->setStatus($recv::STATUS_OK);
        $recv->setData(true);
        return $handelClass->getData($recv);
    }

    /**
     * @return null
     */
    public function getPipe(): ?Pipe
    {
        return $this->pipe;
    }

    /**
     * @param  $pipe
     */
    public function setPipe(Pipe $pipe): void
    {
        if (!$this->getPipe() instanceof Pipe) {
            $this->pipe = $pipe;
        }
    }

    ######################管道操作方法######################


    ######################脚本操作方法(待测试)######################

    /*public function eval($script, $keyNum, $key,...$data)
    {
        $command = array_merge([Command::EVAL,$script, $keyNum, $key,], $data);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function evalSha($sha1,$keyNum,$key,...$data)
    {
        $command = array_merge([Command::EVAL,$sha1, $keyNum, $key,], $data);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function scriptExists($script,...$scripts)
    {
        $command = array_merge([Command::SCRIPT_EXISTS,$script], $scripts);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function scriptFlush()
    {
        $data = [Command::SCRIPT_FLUSH];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }

    public function scriptKill():bool
    {
        $data = [Command::SCRIPT_KILL];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return true;
    }

    public function scriptLoad($script)
    {
        $data = [Command::SCRIPT_LOAD,$script];
        if (!$this->sendCommand($data)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv->getData();
    }*/
    ######################脚本操作方法(待测试)######################


    ######################脚本操作方法(待测试)######################


    ######################服务器操作方法######################

    public function bgRewriteAof()
    {
        $handelClass = new BgRewriteAof($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function bgSave()
    {
        $handelClass = new BgSave($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function clientKill($data): bool
    {
        $handelClass = new ClientKill($this);
        $command = $handelClass->getCommand($data);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function clientList()
    {
        $handelClass = new ClientList($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function clientGetName()
    {
        $handelClass = new ClientGetName($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function clientPause($timeout): bool
    {
        $handelClass = new ClientPause($this);
        $command = $handelClass->getCommand($timeout);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function clientSetName($connectionName): bool
    {
        $handelClass = new ClientSetName($this);
        $command = $handelClass->getCommand($connectionName);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function command()
    {
        $handelClass = new \EasySwoole\Redis\CommandHandel\Command($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function commandCount()
    {
        $handelClass = new CommandCount($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function commandGetKeys(...$data)
    {
        $handelClass = new CommandGetKeys($this);
        $command = $handelClass->getCommand(...$data);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function time()
    {
        $handelClass = new Time($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function commandInfo($commandName, ...$commandNames)
    {
        $handelClass = new CommandInfo($this);
        $command = $handelClass->getCommand($commandName, ...$commandNames);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function configGet($parameter)
    {
        $handelClass = new ConfigGet($this);
        $command = $handelClass->getCommand($parameter);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function configRewrite(): bool
    {
        $handelClass = new ConfigRewrite($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function configSet($parameter, $value): bool
    {
        $handelClass = new ConfigSet($this);
        $command = $handelClass->getCommand($parameter, $value);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function configResetStat(): bool
    {
        $handelClass = new ConfigResetStat($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function dBSize()
    {
        $handelClass = new DBSize($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function debugObject($key)
    {
        $handelClass = new DebugObject($this);
        $command = $handelClass->getCommand($key);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function debugSegfault()
    {
        $handelClass = new DebugSegfault($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function flushAll(): bool
    {
        $handelClass = new FlushAll($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function flushDb(): bool
    {
        $handelClass = new FlushDb($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function info($section = null)
    {
        $handelClass = new Info($this);
        $command = $handelClass->getCommand($section);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function lastSave()
    {
        $handelClass = new LastSave($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function monitor(callable $callback)
    {
        $handelClass = new Monitor($this);
        $command = $handelClass->getCommand($callback);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    /**
     * @return bool
     */
    public function isMonitorStop(): bool
    {
        return $this->monitorStop;
    }

    /**
     * @param bool $monitorStop
     */
    public function setMonitorStop(bool $monitorStop): void
    {
        $this->monitorStop = $monitorStop;
    }

    public function role()
    {
        $handelClass = new Role($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function save(): bool
    {
        $handelClass = new Save($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function shutdown()
    {
        $handelClass = new Shutdown($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function slowLog($subCommand, ...$argument)
    {
        $handelClass = new SlowLog($this);
        $command = $handelClass->getCommand($subCommand, ...$argument);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function SYNC()
    {
        $handelClass = new SYNC($this);
        $command = $handelClass->getCommand();

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }
    ######################服务器操作方法######################

    ######################geohash操作方法######################
    /**
     * geoAdd
     * @param $key
     * @param $locationData [[longitude=>'',latitude=>'',name=>''],[longitude=>'',latitude=>'',name=>'']] or $locationData[[longitude,latitude,name],[longitude,latitude,name],]
     * @return bool|string
     * @throws RedisException
     * @author Tioncico
     * Time: 11:20
     */
    public function geoAdd($key, $locationData)
    {
        $handelClass = new GeoAdd($this);
        $command = $handelClass->getCommand($key, $locationData);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function geoDist($key, $location1, $location2, $unit = 'm')
    {
        $handelClass = new GeoDist($this);
        $command = $handelClass->getCommand($key, $location1, $location2, $unit);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function geoHash($key, $location, ...$locations)
    {
        $handelClass = new GeoHash($this);
        $command = $handelClass->getCommand($key, $location, ...$locations);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function geoPos($key, $location1, ...$locations)
    {
        $handelClass = new GeoPos($this);
        $command = $handelClass->getCommand($key, $location1, ...$locations);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function geoRadius($key, $longitude, $latitude, $radius, $unit = 'm', $withCoord = false, $withDist = false, $withHash = false, $count = null, $sort = null, $storeKey = null, $storeDistKey = null)
    {
        $handelClass = new GeoRadius($this);
        $command = $handelClass->getCommand($key, $longitude, $latitude, $radius, $unit, $withCoord, $withDist, $withHash, $count, $sort, $storeKey, $storeDistKey);

        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }

    public function geoRadiusByMember($key, $location, $radius, $unit = 'm', $withCoord = false, $withDist = false, $withHash = false, $count = null, $sort = null, $storeKey = null, $storeDistKey = null)
    {
        $handelClass = new GeoRadiusByMember($this);
        $command = $handelClass->getCommand($key, $location, $radius, $unit, $withCoord, $withDist, $withHash, $count, $sort, $storeKey, $storeDistKey);
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $handelClass->getData($recv);
    }
    ######################geohash操作方法######################


    ###################### 发送接收tcp流数据 ######################
    public function sendCommand(array $com): bool
    {
        //重置次数
        $this->tryConnectTimes = 0;
        //管道拦截
        if ($this->getPipe() instanceof Pipe && $this->getPipe()->isStartPipe()) {
            return true;
        }
        while ($this->tryConnectTimes <= $this->config->getReconnectTimes()) {
            if ($this->connect()) {
                if ($this->client->sendCommand($com)) {
                    $this->reset();
                    return true;
                }
            }
            $this->disconnect();
            $this->tryConnectTimes++;
        }
        /*
         * 链接超过重连次数，应该抛出异常
         */
        throw new RedisException("connect to redis host {$this->config->getHost()}:{$this->config->getPort()} fail after retry {$this->tryConnectTimes} times");
    }

    public function recv($timeout = null): ?Response
    {
        //管道拦截
        if ($this->getPipe() instanceof Pipe && $this->getPipe()->isStartPipe()) {
            $recv = new Response();
            $recv->setData('PIPE');
            $recv->setStatus($recv::STATUS_OK);
            return $recv;
        }
        $recv = $this->client->recv($timeout ?? $this->config->getTimeout());
        if ($recv->getStatus() == $recv::STATUS_ERR) {
            //redis错误,直接报错
            $this->setErrorType($recv->getErrorType());
            $this->setErrorMsg($recv->getMsg());
            throw new RedisException($recv->getMsg());
        } elseif ($recv->getStatus() == $recv::STATUS_OK) {
            return $recv;
        } elseif ($recv->getStatus() == $recv::STATUS_TIMEOUT) {
            $this->setErrorType($recv->getErrorType());
            $this->setErrorMsg($recv->getMsg());
            $this->disconnect();
            throw new RedisException($recv->getMsg());
        }
        return null;
    }

    public function rawCommand(array $command)
    {
        if (!$this->sendCommand($command)) {
            return false;
        }
        $recv = $this->recv();
        if ($recv === null) {
            return false;
        }
        return $recv;
    }
    ###################### 发送接收tcp流数据 ######################

    /**
     * @return mixed
     */
    public function getLastSocketError()
    {
        return $this->lastSocketError;
    }

    /**
     * @return mixed
     */
    public function getLastSocketErrno()
    {
        return $this->lastSocketErrno;
    }

    /**
     * @return mixed
     */
    public function getErrorType()
    {
        return $this->errorType;
    }

    /**
     * @return mixed
     */
    public function getErrorMsg()
    {
        return $this->errorMsg;
    }

    /**
     * @param mixed $lastSocketError
     */
    public function setLastSocketError($lastSocketError): void
    {
        $this->lastSocketError = $lastSocketError;
    }

    /**
     * @param mixed $lastSocketErrno
     */
    public function setLastSocketErrno($lastSocketErrno): void
    {
        $this->lastSocketErrno = $lastSocketErrno;
    }

    /**
     * @param mixed $errorType
     */
    public function setErrorType($errorType): void
    {
        $this->errorType = $errorType;
    }

    /**
     * @param mixed $errorMsg
     */
    public function setErrorMsg($errorMsg): void
    {
        $this->errorMsg = $errorMsg;
    }

    /**
     * @return RedisConfig
     */
    public function getConfig(): RedisConfig
    {
        return $this->config;
    }

}
