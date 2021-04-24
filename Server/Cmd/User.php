<?php
namespace Server\Cmd;

use Server\Factory\FactoryRedis;
use Server\Lib\ServerException;

class User{
    public function login($username,$password){
        if($username == 'admin' && $password == '123456'){
            $token = strtoupper(uniqid());
            FactoryRedis::cache()->set($token,$username);
            return ['token'=>$token];
        }
        throw new ServerException('密码错误');
    }
}
