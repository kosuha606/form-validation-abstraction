<?php
declare(strict_types=1);

namespace kosuha606\FormValidationAbstraction\Common;

use Assert\Assertion;
use ReflectionClass;

/**
 * Static factory for all classes of this package
 * @package kosuha606\HtmlUniParser
 */
final class Factory extends BaseObject
{
    /**
     * @param $config
     * @return mixed
     * @throws \Assert\AssertionFailedException
     * @throws \ReflectionException
     */
    public static function createObject($classConfig, $constuctorArguments = []): BaseObject
    {
        Assertion::keyExists($classConfig, 'class', 'Class key is required for Factory method');
        $class = $classConfig['class'];
        unset($classConfig['class']);
        if ($constuctorArguments) {
            $reflector = new ReflectionClass($class);
            $object = $reflector->newInstanceArgs($constuctorArguments);
        } else {
            $object = new $class($classConfig);
        }
        Assertion::isInstanceOf($object, BaseObject::class, 'Only BaseObject instance can be created by this factory');
        return $object;
    }
}