<?php
namespace Server\Lib;

class Response{
    private $cmd;
    private $token;
    private $protocol;

    public function __construct()
    {
        $this->protocol = new Protocol();
    }

    public function getCmd(){
        return $this->cmd;
    }

    public function setCmd($item){
        $this->cmd = $item;
    }

    public function getToken(){
        return $this->token;
    }

    public function setToken($item){
        $this->token = $item;
    }

    public function success(array $data){
        $data = [
            'v'         => '1.0.0',
            'time'      => time(),
            'token'     => $this->getToken(),
            'cmd'       => $this->getCmd(),
            'err_code'  => 0,
            'err_msg'   => 'ok',
            'body'      => $data
        ];
        return $this->protocol->encode($data);
    }

    public function error($err_code,$err_msg){
        $data = [
            'v'         => '1.0.0',
            'time'      => time(),
            'token'     => $this->getToken(),
            'cmd'       => $this->getCmd(),
            'err_code'  => $err_code,
            'err_msg'   => $err_msg,
            'body'      => null
        ];
        return $this->protocol->encode($data);
    }

}