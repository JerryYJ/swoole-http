<?php

use Server\HttpServer;
use Swoole\Process;

require_once ROOT_PATH . '/vendor/autoload.php';

if ($argc=2) {
    $cmd = $argv[1];
    if ($cmd == "start") {
        $http = new HttpServer();
    } elseif ($cmd == "stop") {
        $pid = intval(file_get_contents(ROOT_PATH. '/runtime/master.pid'));
        if (!empty($pid)) {
            Process::kill($pid);
        }
    } else {
        echo '无效命令' . PHP_EOL;
    }
}