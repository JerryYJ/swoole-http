<?php

use Swoole\Server;

$http = new Swoole\Http\Server('0.0.0.0', 9501);

$http->on('request', function ($request, $response) {

});

$http->on('Start', function (Server $server) {
   $pid = $server->master_pid;
   file_put_contents('./runtime/master.pid', $pid);
});

$http->start();