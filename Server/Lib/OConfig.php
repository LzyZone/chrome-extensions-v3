<?php
namespace Server\Lib;

class OConfig {
    private static $config;
    public static function init($config){
        self::$config = $config;
    }
    public static function getConfig(){
        return self::$config;
    }

    public static function getValue($key){
        $keys = explode('.',$key);
        $config = self::$config;
        foreach ($keys as $key){
            if(!isset($config[$key])){
                $config = false;
                break;
            }
            $config = $config[$key];
        }
        return $config;
    }
}