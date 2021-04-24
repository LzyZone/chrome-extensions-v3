<?php
namespace Server\Cmd;

<<<<<<< HEAD
use Server\Factory\FactoryRedis;
=======
use Server\Lib\ORedis;
>>>>>>> ef5616327c20161ef96f769993449daabe998db5
use Server\Lib\ServerException;

class User{
    const KEY = "USER:%s";
    public function login($username,$password){
        if($username == 'admin' && $password == '123456'){
            $token = strtoupper(uniqid());
<<<<<<< HEAD
            FactoryRedis::cache()->set($token,$username);
=======
            $key = sprintf(self::KEY,$token);
            ORedis::getInstance()->setex($key,86400,$username);
>>>>>>> ef5616327c20161ef96f769993449daabe998db5
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
