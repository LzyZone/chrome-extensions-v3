<?php
namespace Server\Lib;
class ORedis{
    /**
     * @var \Redis|null
     */
    private static $instance = null;

    /**
     * @return \Redis|null
     */
    public static function getInstance(){
        if(self::$instance == null){
            $redis = new \Redis();
            $redis_config = OConfig::getValue('redis');
            $redis->connect($redis_config['host'],$redis_config['port']);
            if(!empty($redis_config['password'])){
                $redis->auth($redis_config['password']);
            }
            if(!empty($redis_config['db'])){
                $redis->select($redis_config['db']);
            }
            self::$instance = $redis;
        }
        return self::$instance;
    }
}
