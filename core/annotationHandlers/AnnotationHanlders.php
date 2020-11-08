<?php


namespace Core\annotationHandlers;

use Core\annotations\Beans;
use Core\annotations\Value;
use ReflectionProperty;

return [
    // class annotation
    Beans::class=>function($instance, $container, $self) {
        $vars = get_object_vars($self);
        $beanName = '';
        if (!empty($vars["name"])) {
            $beanName = $vars["name"];
        } else {
            $arrs = explode("\\", get_class($instance));
            $beanName = end($arrs);
        }
        $container->set($beanName, $instance);
    },
    // property annotation
    Value::class=> function(ReflectionProperty $prop, $instance, $self) {
        $env = parse_ini_file(ROOT_PATH . "/env");
        if (empty($env[$self->name])) {
            return $instance;
        }

        $prop->setValue($instance, $env[$self->name]);
        return $instance;
    }
];