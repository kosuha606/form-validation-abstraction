<?php

namespace kosuha606\FormValidationAbstraction\Handler;

use kosuha606\FormValidationAbstraction\Form\FormInterface;

interface HandlerInterface
{
    public function handle(FormInterface $form);
}