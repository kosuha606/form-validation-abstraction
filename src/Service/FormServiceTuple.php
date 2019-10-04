<?php

namespace app\local\bundles\publicopen\form;

use kosuha606\FormValidationAbstraction\Common\BaseObject;
use kosuha606\FormValidationAbstraction\Service\FormServiceTupleInterface;

/**
 * Class FormServiceTuple
 * @package app\local\bundles\publicopen\form
 */
class FormServiceTuple extends BaseObject implements FormServiceTupleInterface
{
    /**
     * @var
     */
    private $result;

    /**
     * @var
     */
    private $error;

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param mixed $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }
}