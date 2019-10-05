<?php

namespace kosuha606\FormValidationAbstraction\Service;

interface FormServiceTupleInterface
{
    public function getResult();

    public function getError();

    public function getHandlerResult();

    public function setResult($result);

    public function setError($error);

    public function setHandlerResult($handlerResult);
}