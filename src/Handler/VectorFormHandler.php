<?php

namespace kosuha606\FormValidationAbstraction\Handler;

use kosuha606\FormValidationAbstraction\Common\BaseObject;
use kosuha606\FormValidationAbstraction\Form\FormInterface;
use kosuha606\FormValidationAbstraction\Form\VectorForm;
use kosuha606\FormValidationAbstraction\Service\FormService;

/**
 * Class VectorFormHandler
 * @package kosuha606\FormValidationAbstraction\Handler
 */
class VectorFormHandler extends BaseObject implements HandlerInterface
{
    /**
     * @param FormInterface $form
     * @return array
     * @throws \Assert\AssertionFailedException
     * @throws \ReflectionException
     */
    public function handle(FormInterface $form)
    {
        /** @var VectorForm $form */
        $result = [];
        foreach ($form->handlers as $formType => $handlerClass) {
            $result[$formType] = $this->processOneForm($form, $formType, $handlerClass);
        }

        return $result;
    }

    /**
     * @param $form
     * @param $formType
     * @param $handlerClass
     * @return array
     * @throws \Assert\AssertionFailedException
     * @throws \ReflectionException
     */
    private function processOneForm($form, $formType, $handlerClass)
    {
        /** @var HandlerInterface $handlerInst */
        $handlerInst = new $handlerClass;
        $formClass = $form->formNames[$formType];
        if ($handlerInst instanceof VectorHandlerInterface) {
            $handlerInst->beforeVectorForm();
        }
        $result = [
            'result' => true,
            'error' => [],
        ];
        foreach ($form->data[$formType] as $formData) {
            /** @var FormInterface $formInst */
            $formInst = new $formClass;
            $resultTuple = FormService::getInstance()->processForm(
                $formInst,
                $handlerInst,
                $formData
            );
            $result['result'] = $result['result'] && $resultTuple->getResult();
            $result['error'][] = $resultTuple->getError();
        }
        if ($handlerInst instanceof VectorHandlerInterface) {
            $handlerInst->afterVectorForm($result);
        }

        return $result;
    }
}