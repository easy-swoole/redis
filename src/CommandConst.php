<?php
/**
 * Created by PhpStorm.
 * User: Tioncico
 * Date: 2019/9/28 0028
 * Time: 11:38
 */

namespace EasySwoole\Redis;


class CommandConst
{
    const DEL = 'DEL';//该命令用于在 key 存在是删除 key。
    const UNLINK = 'UNLINK';//非阻塞删除key。
    const DUMP = 'DUMP';//序列化给定 key ，并返回被序列化的值。
    const EXISTS = 'EXISTS';//检查给定 key 是否存在。
    const EXPIRE = 'EXPIRE';//为给定 key 设置过期时间。
    const EXPIREAT = 'EXPIREAT';//EXPIREAT 的作用和 EXPIRE 类似，都用于为 key 设置过期时间。 不同在于 EXPIREAT 命令接受的时间参数是 UNIX 时间戳(unix timestamp)。
    const PEXPIRE = 'PEXPIRE';//设置 key 的过期时间亿以毫秒计。
    const PEXPIREAT = 'PEXPIREAT';//设置 key 过期时间的时间戳(unix timestamp) 以毫秒计
    const KEYS = 'KEYS';//查找所有符合给定模式( pattern)的 key 。
    const MOVE = 'MOVE';//将当前数据库的 key 移动到给定的数据库 db 当中。
    const PERSIST = 'PERSIST';//移除 key 的过期时间，key 将持久保持。
    const PTTL = 'PTTL';//以毫秒为单位返回 key 的剩余的过期时间。
    const TTL = 'TTL';//以秒为单位，返回给定 key 的剩余生存时间(TTL, time to live)。
    const RANDOMKEY = 'RANDOMKEY';//从当前数据库中随机返回一个 key 。
    const RENAME = 'RENAME';//修改 key 的名称
    const RENAMENX = 'RENAMENX';//仅当 newkey 不存在时，将 key 改名为 newkey 。
    const TYPE = 'TYPE';//返回 key 所储存的值的类型。


    const SET = 'SET';//设置指定 key 的值
    const GET = 'GET';//获取指定 key 的值。
    const GETRANGE = 'GETRANGE';//返回 key 中字符串值的子字符
    const GETSET = 'GETSET';//将给定 key 的值设为 value ，并返回 key 的旧值(old value)。
    const MGET = 'MGET';//获取所有(一个或多个)给定 key 的值。
    const SETEX = 'SETEX';//将值 value 关联到 key ，并将 key 的过期时间设为 seconds (以秒为单位)。
    const SETNX = 'SETNX';//只有在 key 不存在时设置 key 的值。
    const SETRANGE = 'SETRANGE';//用 value 参数覆写给定 key 所储存的字符串值，从偏移量 offset 开始。
    const STRLEN = 'STRLEN';//返回 key 所储存的字符串值的长度。
    const MSET = 'MSET';//同时设置一个或多个 key-value 对。
    const MSETNX = 'MSETNX';//同时设置一个或多个 key-value 对，当且仅当所有给定 key 都不存在。
    const PSETEX = 'PSETEX';//这个命令和 SETEX 命令相似，但它以毫秒为单位设置 key 的生存时间，而不是像 SETEX 命令那样，以秒为单位。
    const INCR = 'INCR';//将 key 中储存的数字值增一。
    const INCRBY = 'INCRBY';//将 key 所储存的值加上给定的增量值（increment） 。
    const INCRBYFLOAT = 'INCRBYFLOAT';//将 key 所储存的值加上给定的浮点增量值（increment） 。
    const DECR = 'DECR';//将 key 中储存的数字值减一。
    const DECRBY = 'DECRBY';//key 所储存的值减去给定的减量值（decrement） 。
    const APPEND = 'APPEND';//如果 key 已经存在并且是一个字符串， APPEND 命令将 value 追加到 key 原来的值的末尾。
    const SCAN = 'SCAN';//


    const HDEL = 'HDEL';//删除一个或多个哈希表字段
    const HEXISTS = 'HEXISTS';//查看哈希表 key 中，指定的字段是否存在。
    const HGET = 'HGET';//获取存储在哈希表中指定字段的值/td>
    const HGETALL = 'HGETALL';//获取在哈希表中指定 key 的所有字段和值
    const HINCRBY = 'HINCRBY';//为哈希表 key 中的指定字段的整数值加上增量 increment 。
    const HINCRBYFLOAT = 'HINCRBYFLOAT';//为哈希表 key 中的指定字段的浮点数值加上增量 increment 。
    const HKEYS = 'HKEYS';//获取所有哈希表中的字段
    const HLEN = 'HLEN';//获取哈希表中字段的数量
    const HMGET = 'HMGET';//获取所有给定字段的值
    const HMSET = 'HMSET';//同时将多个 field-value (域-值)对设置到哈希表 key 中。
    const HSET = 'HSET';//将哈希表 key 中的字段 field 的值设为 value 。
    const HSETNX = 'HSETNX';//只有在字段 field 不存在时，设置哈希表字段的值。
    const HVALS = 'HVALS';//获取哈希表中所有值
    const HSCAN = 'HSCAN';//迭代哈希表中的键值对。


    const BLPOP = 'BLPOP';//移出并获取列表的第一个元素， 如果列表没有元素会阻塞列表直到等待超时或发现可弹出元素为止。
    const BRPOP = 'BRPOP';//移出并获取列表的最后一个元素， 如果列表没有元素会阻塞列表直到等待超时或发现可弹出元素为止。
    const BRPOPLPUSH = 'BRPOPLPUSH';//从列表中弹出一个值，将弹出的元素插入到另外一个列表中并返回它； 如果列表没有元素会阻塞列表直到等待超时或发现可弹出元素为止。
    const LINDEX = 'LINDEX';//通过索引获取列表中的元素
    const LINSERT = 'LINSERT';//在列表的元素前或者后插入元素
    const LLEN = 'LLEN';//获取列表长度
    const LPOP = 'LPOP';//移出并获取列表的第一个元素
    const LPUSH = 'LPUSH';//将一个或多个值插入到列表头部
    const LPUSHX = 'LPUSHX';//将一个或多个值插入到已存在的列表头部
    const LRANGE = 'LRANGE';//获取列表指定范围内的元素
    const LREM = 'LREM';//移除列表元素
    const LSET = 'LSET';//通过索引设置列表元素的值
    const LTRIM = 'LTRIM';//对一个列表进行修剪(trim)，就是说，让列表只保留指定区间内的元素，不在指定区间之内的元素都将被删除。
    const RPOP = 'RPOP';//移除并获取列表最后一个元素
    const RPOPLPUSH = 'RPOPLPUSH';//移除列表的最后一个元素，并将该元素添加到另一个列表并返回
    const RPUSH = 'RPUSH';//在列表中添加一个或多个值
    const RPUSHX = 'RPUSHX';//为已存在的列表添加值


    const SADD = 'SADD';//向集合添加一个或多个成员
    const SCARD = 'SCARD';//获取集合的成员数
    const SDIFF = 'SDIFF';//返回给定所有集合的差集
    const SDIFFSTORE = 'SDIFFSTORE';//返回给定所有集合的差集并存储在 destination 中
    const SINTER = 'SINTER';//返回给定所有集合的交集
    const SINTERSTORE = 'SINTERSTORE';//返回给定所有集合的交集并存储在 destination 中
    const SISMEMBER = 'SISMEMBER';//判断 member 元素是否是集合 key 的成员
    const SMEMBERS = 'SMEMBERS';//返回集合中的所有成员
    const SMOVE = 'SMOVE';//将 member 元素从 source 集合移动到 destination 集合
    const SPOP = 'SPOP';//移除并返回集合中的一个或多个随机元素
    const SRANDMEMBER = 'SRANDMEMBER';//返回集合中一个或多个随机数
    const SREM = 'SREM';//移除集合中一个或多个成员
    const SUNION = 'SUNION';//返回所有给定集合的并集
    const SUNIONSTORE = 'SUNIONSTORE';//所有给定集合的并集存储在 destination 集合中
    const SSCAN = 'SSCAN';//迭代集合中的元素

    const GETBIT = 'GETBIT';//对 key 所储存的字符串值，获取指定偏移量上的位(bit)。
    const SETBIT = 'SETBIT';//对 key 所储存的字符串值，设置或清除指定偏移量上的位(bit)。
    const BITCOUNT = 'BITCOUNT';//Count the number of set bits (population counting) in a string.
    const BITPOS = 'BITPOS'; // Return the position of the first bit set to 1 or 0 in a string.
    const BITOP = 'BITOP'; // Perform a bitwise operation between multiple keys (containing string values) and store the result in the destination key.
    const BITFIELD = 'BITFIELD'; // The command treats a Redis string as a array of bits, and is capable of addressing specific integer fields of varying bit widths and arbitrary non (necessary) aligned offset.


    const ZADD = 'ZADD';//向有序集合添加一个或多个成员，或者更新已存在成员的分数
    const ZCARD = 'ZCARD';//获取有序集合的成员数
    const ZCOUNT = 'ZCOUNT';//计算在有序集合中指定区间分数的成员数
    const ZINCRBY = 'ZINCRBY';//有序集合中对指定成员的分数加上增量 increment
    const ZINTERSTORE = 'ZINTERSTORE';//计算给定的一个或多个有序集的交集并将结果集存储在新的有序集合 key 中
    const ZLEXCOUNT = 'ZLEXCOUNT';//在有序集合中计算指定字典区间内成员数量
    const ZRANGE = 'ZRANGE';//通过索引区间返回有序集合成指定区间内的成员
    const ZPOPMAX = 'ZPOPMAX';//删除并返回有序集合中的一个或多个具有最高得分的成员
    const ZPOPMIN = 'ZPOPMIN';//删除并返回有序集合中的一个或多个具有最低得分的成员
    const ZRANGEBYLEX = 'ZRANGEBYLEX';//通过字典区间返回有序集合的成员
    const ZRANGEBYSCORE = 'ZRANGEBYSCORE';//通过分数返回有序集合指定区间内的成员
    const ZRANK = 'ZRANK';//返回有序集合中指定成员的索引
    const ZREM = 'ZREM';//移除有序集合中的一个或多个成员
    const ZREMRANGEBYLEX = 'ZREMRANGEBYLEX';//移除有序集合中给定的字典区间的所有成员
    const ZREMRANGEBYRANK = 'ZREMRANGEBYRANK';//移除有序集合中给定的排名区间的所有成员
    const ZREMRANGEBYSCORE = 'ZREMRANGEBYSCORE';//移除有序集合中给定的分数区间的所有成员
    const ZREVRANGE = 'ZREVRANGE';//返回有序集中指定区间内的成员，通过索引，分数从高到底
    const ZREVRANGEBYSCORE = 'ZREVRANGEBYSCORE';//返回有序集中指定分数区间内的成员，分数从高到低排序
    const ZREVRANK = 'ZREVRANK';//返回有序集合中指定成员的排名，有序集成员按分数值递减(从大到小)排序
    const ZSCORE = 'ZSCORE';//返回有序集中，成员的分数值
    const ZUNIONSTORE = 'ZUNIONSTORE';//计算给定的一个或多个有序集的并集，并存储在新的 key 中
    const ZSCAN = 'ZSCAN';//迭代有序集合中的元素（包括元素成员和元素分值）
    const BZPOPMAX = 'BZPOPMAX';
    const BZPOPMIN = 'BZPOPMIN';


    const XADD = 'XADD'; //向指定stream添加数据
    const XLEN = 'XLEN'; //返回指定stream的item的个数
    const XDEL = 'XDEL'; //删除指定steam的item
    const XRANGE = 'XRANGE';//查询指定stream范围内的item
    const XREVRANGE = 'XREVRANGE';//同上 不过是倒叙输出
    const XREAD = 'XREAD';//监听指定stream
    const XGROUP = 'XGROUP';//创建 管理 删除group
    const XREADGROUP = 'XREADGROUP';//读取消息进行消费
    const XACK = 'XACK'; //通知group成功处理消息
    const XINFO = 'XINFO'; //获取stream或者group信息
    const XCLAIM = 'XCLAIM'; //改变message的所属关系
    const XPENDING = 'XPENDING';//查看组内的pending message信息
    const XTRIM = 'XTRIM';//将流修剪为给定数量的项目

    const PFADD = 'PFADD';//添加指定元素到 HyperLogLog 中。
    const PFCOUNT = 'PFCOUNT';//返回给定 HyperLogLog 的基数估算值。
    const PFMERGE = 'PFMERGE';//将多个 HyperLogLog 合并为一个 HyperLogLog

    const PSUBSCRIBE = 'PSUBSCRIBE';//订阅一个或多个符合给定模式的频道。
    const PUBSUB = 'PUBSUB';//查看订阅与发布系统状态。
    const PUBLISH = 'PUBLISH';//将信息发送到指定的频道。
    const PUNSUBSCRIBE = 'PUNSUBSCRIBE';//退订所有给定模式的频道。
    const SUBSCRIBE = 'SUBSCRIBE';//订阅给定的一个或多个频道的信息。
    const UNSUBSCRIBE = 'UNSUBSCRIBE';//指退订给定的频道。

    const DISCARD = 'DISCARD';//取消事务，放弃执行事务块内的所有命令。
    const EXEC = 'EXEC';//执行所有事务块内的命令。
    const MULTI = 'MULTI';//标记一个事务块的开始。
    const UNWATCH = 'UNWATCH';//取消 WATCH 命令对所有 key 的监视。
    const WATCH = 'WATCH';//监视一个(或多个) key ，如果在事务执行之前这个(或这些) key 被其他命令所改动，那么事务将被打断。


    const EVAL = 'EVAL';//执行 Lua 脚本。
    const EVALSHA = 'EVALSHA';//执行 Lua 脚本。
    const SCRIPT = 'SCRIPT';//查看指定的脚本是否已经被保存在缓存当中。
    const SCRIPT_EXISTS = 'SCRIPT EXISTS';//查看指定的脚本是否已经被保存在缓存当中。
    const SCRIPT_FLUSH = 'SCRIPT FLUSH';//查看指定的脚本是否已经被保存在缓存当中。
    const SCRIPT_KILL = 'SCRIPT KILL';//查看指定的脚本是否已经被保存在缓存当中。
    const SCRIPT_LOAD = 'SCRIPT LOAD';//查看指定的脚本是否已经被保存在缓存当中。


    const AUTH = 'AUTH';//验证密码是否正确
    const ECHO = 'ECHO';//打印字符串
    const PING = 'PING';//查看服务是否运行
    const QUIT = 'QUIT';//关闭当前连接
    const SELECT = 'SELECT';//切换到指定的数据库


    const BGREWRITEAOF = 'BGREWRITEAOF';//异步执行一个 AOF（AppendOnly File） 文件重写操作
    const BGSAVE = 'BGSAVE';//在后台异步保存当前数据库的数据到磁盘
    const CLIENT = 'CLIENT';//关闭客户端连接
    const COMMAND = 'COMMAND';//获取 Redis 命令详情数组
    const COMMAND_COUNT = 'COMMAND COUNT';//获取 Redis 命令详情数组
    const TIME = 'TIME';//返回当前服务器时间
    const CONFIG = 'CONFIG';//获取指定配置参数的值
    const DBSIZE = 'DBSIZE';//返回当前数据库的 key 的数量
    const DEBUG = 'DEBUG';//获取 key 的调试信息
    const FLUSHALL = 'FLUSHALL';//删除所有数据库的所有key
    const FLUSHDB = 'FLUSHDB';//删除当前数据库的所有key
    const INFO = 'INFO';//获取 Redis 服务器的各种信息和统计数值
    const LASTSAVE = 'LASTSAVE';//返回最近一次 Redis 成功将数据保存到磁盘上的时间，以 UNIX 时间戳格式表示
    const MONITOR = 'MONITOR';//实时打印出 Redis 服务器接收到的命令，调试用
    const ROLE = 'ROLE';//返回主从实例所属的角色
    const SAVE = 'SAVE';//异步保存数据到硬盘
    const SHUTDOWN = 'SHUTDOWN';//异步保存数据到硬盘，并关闭服务器
    const SLAVEOF = 'SLAVEOF';//将当前服务器转变为指定服务器的从属服务器(slave server)
    const SLOWLOG = 'SLOWLOG';//管理 redis 的慢日志
    const SYNC = 'SYNC';//用于复制功能(replication)的内部命令


    const GEOADD = 'GEOADD';
    const GEODIST = 'GEODIST';
    const GEOHASH = 'GEOHASH';
    const GEOPOS = 'GEOPOS';
    const GEORADIUS = 'GEORADIUS';
    const GEORADIUSBYMEMBER = 'GEORADIUSBYMEMBER';


    const CLUSTER = 'CLUSTER';//获取集群节点的映射数组
    const READONLY = 'READONLY';//集群相关命令
    const READWRITE = 'READWRITE';//集群相关命令
}
