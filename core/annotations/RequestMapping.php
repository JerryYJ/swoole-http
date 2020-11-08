<?php


namespace Core\annotations;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class RequestMapping
 * @Annotation
 * @Target({"METHOD"})
 * @package Core\annotations
 */
class RequestMapping
{
    public $value=""; // 路径
    public $method=[]; // 请求类型
}