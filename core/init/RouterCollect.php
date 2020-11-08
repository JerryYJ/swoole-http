<?php


namespace Core\init;

use Core\annotations\Beans;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

/**
 * 路由收集器
 * Class RouterCollect
 * @Beans()
 * @package Core\init
 */
class RouterCollect
{
    public $routers=[];

    public function addRouter($method, $uri, $handler)
    {
        $this->routers[] = ['method'=>$method, 'uri'=> $uri, 'handler'=> $handler];
    }

    public function getDispatcher()
    {
        return simpleDispatcher(function(RouteCollector $r) {
            foreach ($this->routers as $router) {
                $r->addRoute($router['method'], $router['uri'], $router['handler']);
            }
        });
    }
}