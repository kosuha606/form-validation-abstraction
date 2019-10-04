<?php


namespace kosuha606\FormValidationAbstraction\Handler;


interface VectorHandlerInterface
{
    public function beforeVectorForm();

    public function afterVectorForm($result);
}