<?php


namespace Core\annotations;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class Value
 * @Annotation
 * @Target({"PROPERTY"})
 * @package Core\annotations
 */
class Value
{
    public $name="";
}