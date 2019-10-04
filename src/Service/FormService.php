<?php

namespace kosuha606\FormValidationAbstraction\Service;

use app\local\bundles\publicopen\form\FormServiceTuple;
use kosuha606\FormValidationAbstraction\Common\Factory;
use kosuha606\FormValidationAbstraction\Form\FormInterface;
use kosuha606\FormValidationAbstraction\Handler\HandlerInterface;
use stdClass;

/**
 * @package kosuha606\FormValidationAbstraction\Service
 */
class FormService
{
    /** @var FormInterface[] */
    private $forms = [];

    /**
     * @var
     */
    private static $instance;

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
     */
    public function addForm(FormInterface $form)
    {
        $this->forms[$form->name()] = $form;
    }

    /**
     * @param FormInterface $formInst
     * @param HandlerInterface $handlerInst
     * @param array $formDataUnsafe
     * @return FormServiceTupleInterface
     * @throws \Assert\AssertionFailedException
     * @throws \ReflectionException
     */
    public function processForm(
        FormInterface $formInst,
        HandlerInterface $handlerInst,
        $formDataUnsafe = []
    ): FormServiceTupleInterface {
        $formInst->setAttributes($formDataUnsafe);
        $resultFlag = true;
        $errorsData = [];
        if ($formInst->validate()) {
            $handlerInst->handle($formInst);
        } else {
            $resultFlag = false;
            $errorsData[$formInst->name()] = $formInst->getErrors();
        }
        /** @var FormServiceTuple $returnTuple */
        $returnTuple = Factory::createObject(
            [
                'class' => FormServiceTuple::class,
                'result' => $resultFlag,
                'error' => $errorsData,
            ]
        );

        return $returnTuple;
    }
}