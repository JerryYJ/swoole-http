<?php


namespace Core\annotationHandlers;


use Core\annotations\RequestMapping;
use Core\BeanFactory;
use Core\init\RouterCollect;
use ReflectionMethod;

return [
    RequestMapping::class=> function(ReflectionMethod $method, $instance, RequestMapping $self) {
        $path = $self->value;
        $request_method = empty($self->method) ? "GET" : $self->method;

        $routeCollect = BeanFactory::getBean(RouterCollect::class);
        $routeCollect->addRouter($request_method, $path, function ($params, $extParams) use ($method, $instance) {
            $paramsArr = [];
            $refMethodParams = $method->getParameters();
            foreach ($refMethodParams as $parameter) {
                $paramName = $parameter->getName();
                if (!empty($params[$paramName])) {
                    $paramsArr[] = $params[$paramName];
                } else {
                    $tempExtParam = false;
                    foreach ($extParams as $extParam) {
                        if ($parameter->getClass()->isInstance($extParam)) {
                            $tempExtParam = $extParam;
                            continue;
                        }
                    }
                    $paramsArr[] = $tempExtParam;
                }
            }
            return $method->invokeArgs($instance, $paramsArr); // run refection method
        });

        return $instance;
    }
];