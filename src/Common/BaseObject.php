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
        foreach ($config as $key => $value) {
            if (\property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
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
        foreach ($data as $key => $value) {
            if (\property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
