<?php


namespace App\controllers;

use Core\annotations\Beans;
use Core\annotations\RequestMapping;
use Core\annotations\Value;
use Core\http\Request;

/**
 * Class UserController
 * @Beans()
 * @package App\controllers
 */
class UserController
{
    /**
     * @Value(name="version")
     * @var string
     */
    public $version='1.0';

    /**
     * @RequestMapping(value="/test", method={"GET"})
     * @return string
     */
    public function test()
    {
        return "hahahah";
    }

    /**
     * @RequestMapping(value="/abc/{uid:\d+}", method={"GET"})
     * @return string
     */
    public function abc(Request $request, $uid)
    {
        var_dump($request->getQueryParams());
        return "abc:" . $uid;
    }
}