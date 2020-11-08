<?php

use Core\BeanFactory;
use Core\http\Request;
use Core\http\Response;
use Core\init\RouterCollect;

$http = new Swoole\Http\Server('0.0.0.0', 9501);

require_once __DIR__.'/app/config/define.php';
require __DIR__ . '/vendor/autoload.php';

BeanFactory::init();
$dispatcher = BeanFactory::getBean(RouterCollect::class)->getDispatcher();


$http->on('request', function ($request, $response) use ($dispatcher) {
    $myRequest = Request::init($request);
    $myResponse = Response::init($response);
    $routeInfo = $dispatcher->dispatch($myRequest->getMethod(), $myRequest->getUri());

    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::NOT_FOUND:
            $response->status(404);
            $response->end();
            break;
        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            $response->status(405);
            $response->end();
            break;
        case FastRoute\Dispatcher::FOUND:
            $handler = $routeInfo[1];
            $vars = $routeInfo[2];
            $extVars = [$myRequest, $myResponse];
            $myResponse->setBody($handler($vars, $extVars));
            $myResponse->end();
            break;
    }
});

$http->start();