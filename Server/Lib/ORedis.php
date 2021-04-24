<?php
/**
 * Created by PhpStorm.
 * User: lizhiyong
 * Date: 2021-04-24
 * Time: 19:19
 */

namespace Server\Lib;

class ORedis{
    /**
     * @var \Redis
     */
    private $redis;
    private $host;
    private $port;
    private $password;

    public function __construct($host,$port=6379,$password='')
    {
        $this->redis = new \Redis();
        $this->host = $host;
        $this->port = $port;
        $this->password = $password;
    }

    public function select($db=0,$try_cnt=3){
        if($try_cnt <= 0)return false;
        try{
            $this->redis->select($db);
        }catch (\RedisException $e){
            $this->connection();
            $this->select($db,--$try_cnt);
        }
    }

    public function set($key,$value,$try_cnt=3){
        if($try_cnt <= 0)return false;
        try{
            $this->redis->set($key,$value);
        }catch (\RedisException $e){
            $this->connection();
            $this->set($key,$value,--$try_cnt);
        }
    }

    public function get($key,$try_cnt=3){
        if($try_cnt <= 0)return false;
        try{
            $this->redis->get($key);
        }catch (\RedisException $e){
            $this->connection();
            $this->get($key,--$try_cnt);
        }
    }

    public function setex($key,$time,$value,$try_cnt=3){
        if($try_cnt <= 0)return false;
        try{
            $this->redis->setex($key,$time,$value);
        }catch (\RedisException $e){
            $this->connection();
            $this->setex($key,$time,$value,--$try_cnt);
        }
    }

    public function exists($key,$try_cnt=3){
        if($try_cnt <= 0)return false;
        try{
            $this->redis->exists($key);
        }catch (\RedisException $e){
            $this->connection();
            $this->exists($key,--$try_cnt);
        }
    }

    private function connection(){
        try{
            $this->redis->connect($this->host,$this->port);
            $this->password && $this->redis->auth($this->password);
        }catch (\RedisException $e){
            $this->log($e->getMessage());
        }
    }

    private function log($msg){
        echo "redis error,{$msg}".PHP_EOL;
    }

}