<?php

#xdebug_start_trace('/home/www/paranoide.ru/www/xdebug/2.txt');
session_start();

include_once ('./default.conf.php');
include_once (INIT_PATH.'init.php');

global $Core;

$Core = new Init($Config);
$Core->Run();

echo "Hello, git! via NetBeans. kxexkxe";
#xdebug_stop_trace();
?>