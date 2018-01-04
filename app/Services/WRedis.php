<?php

namespace App\Services;

class WRedis
{
    // static可以保存值不丢失
    private static $_instance = null;
    private static $_connect;


    // 使用private防止用户new
    private function __construct()
    {
        $REDIS_HOST = env('REDIS_HOST', '127.0.0.1');
        $REDIS_PORT = env('REDIS_PORT', '6379');

        self::$_connect = new \Redis();
        self::$_connect->connect("{$REDIS_HOST}", "{$REDIS_PORT}");
    }

    // 重写clone防止用户进行clone
    public function __clone(){}

    // 由类的自身来进行实例化
    public static function getInstance()
    {
        if(self::$_instance == null)
        {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * 获取锁
     * @param  String  $key    锁标识
     * @param  Int     $expire 锁过期时间
     * @return Boolean
     */
    public function lock($key, $expire = 5)
    {
        $is_lock = self::$_connect->setnx($key, time() + $expire);

        // 不能获取锁
        if( ! $is_lock)
        {

            // 判断锁是否过期
            $lock_time = self::$_connect->get($key);

            // 锁已过期，删除锁，重新获取
            if(time() > $lock_time)
            {
                $this->unlock($key);
                $is_lock = self::$_connect->setnx($key, time() + $expire);
            }
        }

        return $is_lock ? true : false;
    }

    /**
     * 释放锁
     * @param  String  $key 锁标识
     * @return Boolean
     */
    public function unlock($key)
    {
        return self::$_connect->del($key);
    }

}