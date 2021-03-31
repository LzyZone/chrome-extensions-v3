<?php
namespace Server\Cmd;

use Server\Lib\ORedis;
use Server\Lib\ServerException;

class User{
    const KEY = "USER:%s";
    public function login($username,$password){
        if($username == 'admin' && $password == '123456'){
            $token = strtoupper(uniqid());
            $key = sprintf(self::KEY,$token);
            ORedis::getInstance()->setex($key,86400,$username);
            return ['token'=>$token];
        }
        throw new ServerException('密码错误');
    }

    public function checkLogin($token){
        $key = sprintf(self::KEY,$token);
        $exists = ORedis::getInstance()->exists($key);
        if($exists){
            return true;
        }else{
            false;
        }
    }
}
