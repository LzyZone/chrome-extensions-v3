<?php
namespace Server\Cmd;

use Server\Lib\ServerException;

class User{
    public function login($username,$password){
        if($username == 'admin' && $password == '123456'){
            return ['token'=>strtoupper(uniqid())];
        }
        throw new ServerException('密码错误');
    }
}
