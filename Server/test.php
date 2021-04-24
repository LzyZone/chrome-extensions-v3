<?php
/**
 * Created by PhpStorm.
 * User: lizhiyong
 * Date: 2021-04-24
 * Time: 21:06
 */

require_once 'vendor/autoload.php';


$redis = new \Server\Lib\ORedis('127.0.0.1');


$redis->select(1);