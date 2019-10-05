<?php

use kosuha606\FormValidationAbstraction\Common\BaseObject;
use kosuha606\FormValidationAbstraction\Form\FormInterface;

class DeliveryForm extends AbstractForm
{
    public $city;

    public $street;

    public function name()
    {
        return 'delivery_form';
    }
}