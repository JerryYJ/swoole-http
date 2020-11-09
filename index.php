<?php
require_once __DIR__.'/app/config/define.php';
require __DIR__ . '/vendor/autoload.php';

use Server\HttpServer;

$httpServer = new HttpServer();
$httpServer->run();