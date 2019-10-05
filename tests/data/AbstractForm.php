<?php

use kosuha606\FormValidationAbstraction\Common\BaseObject;
use kosuha606\FormValidationAbstraction\Form\FormInterface;

abstract class AbstractForm extends BaseObject implements FormInterface
{
    protected $errors = [];

    public function validate()
    {
        return true;
    }

    public function name()
    {
        return 'abstract_form';
    }

    public function toArray()
    {
        $result = parent::toArray();
        unset($result['errors']);
        return $result;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}