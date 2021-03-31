<?php
require_once 'init.php';

$server = new Swoole\Websocket\Server('127.0.0.1', 9502);
$server->set(['task_worker_num' => 1]);
$server->on('WorkerStart',function ($server,$worker_id){
    /**
     * global $argv;
    if($worker_id >= $server->setting['worker_num']) {
    swoole_set_process_name("php {$argv[0]} task worker");
    } else {
    swoole_set_process_name("php {$argv[0]} event worker");
    }
     */
    $task_id = $server->task('Async');
    echo "Dispatch AsyncTask: [id={$task_id}]\n";
});

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

        if(empty($data['token']) && $data['cmd'] != 'user.login'){
            throw new \Server\Lib\ServerException('权限认证失败！');
        }

        if($data['token']){
            $isLogin = (new \Server\Cmd\User())->checkLogin($data['token']);
            if(!$isLogin){
                throw new \Server\Lib\ServerException('登录已过期，请重新登录！',6000);
            }
        }
        //check token
        $obj = new $cmd_class();
        $ret = call_user_func_array([$obj,$cmd_class_fun],$data['body']);
        if($data['cmd'] == 'user.login'){
            $response->setToken($ret);
        }
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

$server->on('task', function ($server, $task_id, $reactor_id, $data) {
    echo "New AsyncTask[id={$task_id}]\n";
    $response = new \Server\Lib\Response();
    $response->setCmd('notice');
    while (true){
        $list = \Server\Lib\ORedis::getInstance()->blPop('list',2);
        var_dump($list);
        $conn_list = $server->getClientList();
        var_dump($conn_list);
        if ($conn_list === false or count($conn_list) === 0) {
            echo "finish\n";
            sleep(3);
            continue;
        }

        foreach ($conn_list as $fd) {
            $server->send($fd, $response->success(['msg'=>$list[1]]));
        }
    }
    $server->finish("{$data} -> OK");
});

$server->on('finish', function ($server, $task_id, $data) {
    echo "AsyncTask[{$task_id}] finished: {$data}\n";
});



$server->start();