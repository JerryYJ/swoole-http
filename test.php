<?php

use App\controllers\UserController;
use Core\BeanFactory;
use Core\init\RouterCollect;

require_once __DIR__.'/vendor/autoload.php';

require_once __DIR__.'/app/config/define.php';

BeanFactory::init();

$user = BeanFactory::getBean(RouterCollect::class);
var_dump($user->routers);