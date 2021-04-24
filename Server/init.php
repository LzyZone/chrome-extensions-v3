<?php
define('ROOT_PATH',__DIR__.DIRECTORY_SEPARATOR);
date_default_timezone_set('Asia/Shanghai');
require_once ROOT_PATH.'vendor/autoload.php';
$ini_config = parse_ini_file(ROOT_PATH.'.env',true);
\Server\Lib\OConfig::init($ini_config);
