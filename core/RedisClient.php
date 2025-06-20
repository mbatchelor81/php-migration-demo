<?php
namespace Core;

use Redis;
use RedisException;

class RedisClient
{
    private static ?Redis $redis = null;

    public static function get(): Redis
    {
        if (self::$redis === null) {
            $host = Config::get('REDIS_HOST', '127.0.0.1');
            $port = (int) Config::get('REDIS_PORT', 6379);
            $r = new Redis();
            try {
                $r->connect($host, $port, 2.0);
            } catch (RedisException $e) {
                die('Redis connection failed: ' . $e->getMessage());
            }
            self::$redis = $r;
        }
        return self::$redis;
    }
}
