<?php


namespace Core\annotations;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class Beans
 * @Annotation
 * @Target({"CLASS"})
 * @package App\core\annotations
 */
class Beans
{
    /** @var string */
    public $name="";
}