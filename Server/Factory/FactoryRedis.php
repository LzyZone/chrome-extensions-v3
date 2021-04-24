<?php
/**
 * Created by PhpStorm.
 * User: lizhiyong
 * Date: 2021-04-24
 * Time: 21:23
 */
namespace Server\Factory;

use Server\Lib\ORedis;

class FactoryRedis{
    /**
     * @var null|ORedis
     */
    private static $instances = null;

    /**
     * @return ORedis
     */
    public static function cache(){
        return self::getInstance('127.0.0.1');
    }

    /**
     * @param $host
     * @param int $port
     * @param string $password
     * @return ORedis
     */
    public static function getInstance($host,$port=6379,$password=''){
        $key = md5($host.$port.$password);
        if(!self::$instances[$key]){
            self::$instances[$key] = new ORedis($host,$port,$password);
        }
        return self::$instances[$key];
    }
}