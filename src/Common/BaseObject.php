<?php
declare(strict_types=1);

namespace kosuha606\FormValidationAbstraction\Common;

/**
 * @package app\Parsers
 */
abstract class BaseObject
{
    /**
     * @param $config
     */
    public function __construct($config = [])
    {
        $this->initProperties($config);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }

    /**
     * @param $data
     */
    public function setAttributes($data)
    {
        $this->initProperties($data);
    }

    private function initProperties($config)
    {
        foreach ($config as $key => $value) {
            $methodName = 'set'.ucfirst($key);
            if (method_exists($this, $methodName)) {
                $this->$methodName($value);
            } elseif (\property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
