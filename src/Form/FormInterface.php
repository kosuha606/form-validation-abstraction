<?php

namespace kosuha606\FormValidationAbstraction\Form;

interface FormInterface
{
    public function validate();

    public function toArray();

    public function name();

    public function getErrors();

    public function setAttributes($data);
}