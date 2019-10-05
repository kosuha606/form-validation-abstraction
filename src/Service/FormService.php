<?php

namespace kosuha606\FormValidationAbstraction\Service;

use Assert\AssertionFailedException;
use kosuha606\FormValidationAbstraction\Common\Factory;
use kosuha606\FormValidationAbstraction\Form\FormInterface;
use kosuha606\FormValidationAbstraction\Handler\HandlerInterface;
use ReflectionException;
use stdClass;

/**
 * @package kosuha606\FormValidationAbstraction\Service
 */
class FormService
{
    /**
     * @var static
     */
    private static $instance;

    /**
     * @var FormInterface[]
     */
    private $forms = [];

    /**
     * FormService constructor.
     * @param array $config
     */
    private function __construct($config = [])
    {
    }

    /**
     * @return FormService
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * @return array
     */
    public function serializeForms()
    {
        $result = [];
        foreach ($this->forms as $key => $form) {
            $result[$key] = $form ? $form->toArray() : new stdClass();
        }

        return $result;
    }

    /**
     * @param string $name
     * @param FormInterface $form
     * @return FormService
     */
    public function addForm(FormInterface $form)
    {
        $this->forms[$form->name()] = $form;

        return $this;
    }

    public function clearForms()
    {
        $this->forms = [];

        return $this;
    }

    /**
     * @param FormInterface $formInst
     * @param HandlerInterface $handlerInst
     * @param array $formDataUnsafe
     * @return FormServiceTupleInterface
     * @throws AssertionFailedException
     * @throws ReflectionException
     */
    public function processForm(
        FormInterface $formInst,
        HandlerInterface $handlerInst,
        $formDataUnsafe = []
    ): FormServiceTupleInterface {
        $formInst->setAttributes($formDataUnsafe);
        $resultFlag = true;
        $errorsData = [];
        /** @var FormServiceTuple $returnTuple */
        $returnTuple = Factory::createObject([
            'class' => FormServiceTuple::class
        ]);
        if ($formInst->validate()) {
            $returnTuple->setHandlerResult($handlerInst->handle($formInst));
        } else {
            $resultFlag = false;
            $errorsData[$formInst->name()] = $formInst->getErrors();
        }
        $returnTuple->setResult($resultFlag);
        $returnTuple->setError($errorsData);

        return $returnTuple;
    }
}