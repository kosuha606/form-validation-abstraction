<?php
declare(strict_types=1);

namespace kosuha606\FormValidationAbstraction\Common;

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
     * @throws \ReflectionException
     */
    public static function createObject($classConfig, $constuctorArguments = []): BaseObject
    {
        if (!isset($classConfig['class'])) {
            throw new \Exception('Class key is required for Factory method');
        }
        $class = $classConfig['class'];
        unset($classConfig['class']);
        if ($constuctorArguments) {
            $reflector = new ReflectionClass($class);
            $object = $reflector->newInstanceArgs($constuctorArguments);
        } else {
            $object = new $class($classConfig);
        }
        if (!$object instanceof BaseObject) {
            throw new \Exception('Only BaseObject instance can be created by this factory');
        }

        return $object;
    }
}