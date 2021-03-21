<?php
namespace Server\Lib;
/**
 * @info
 * @auther gary<321539047@qq.com>
 * @date 2021-03-09 22:52
 *
 */
class Protocol{

    public function decode($content){

    }

    public function encode($data){
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }
}