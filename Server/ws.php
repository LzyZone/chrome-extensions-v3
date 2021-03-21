<?php
require_once 'vendor/autoload.php';

$server = new Swoole\Websocket\Server('127.0.0.1', 9502);

$server->on('open', function($server, $req) {
    echo "connection open: {$req->fd}\n";
});

$server->on('message', function($server, $frame) {
    echo "received message: {$frame->data}\n";
    $response = new \Server\Lib\Response();
    $data = json_decode($frame->data,true);
    $response->setCmd($data['cmd']);
    if($data['cmd'] != 'user.login'){
        $response->setToken($data['token']);
    }

    try{
        list($cmd_class,$cmd_class_fun) = explode('.',$data['cmd']);
        $cmd_class = "\\Server\\Cmd\\".ucfirst($cmd_class);
        if(!class_exists($cmd_class)){
            throw new \Server\Lib\ServerException('server error');
        }

        $data['cmd'] = strtolower($data['cmd']);

        if(empty($data['token']) && strtolower($data['cmd']) != 'user.login'){
            throw new \Server\Lib\ServerException('权限认证失败！');
        }

        //check token
        $obj = new $cmd_class();
        $ret = call_user_func_array([$obj,$cmd_class_fun],$data['body']);
        $server->push($frame->fd,$response->success($ret));
    }catch (\Server\Lib\ServerException $e){
        $err_code = $e->getCode() ? $e->getCode() : 1000;
        $server->push($frame->fd,$response->error($err_code,$e->getMessage()));
        $server->close($frame->fd);
    }

});

$server->on('close', function($server, $fd) {
    echo "connection close: {$fd}\n";
});

$server->start();