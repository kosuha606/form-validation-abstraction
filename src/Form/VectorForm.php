<?php

namespace kosuha606\FormValidationAbstraction\Form;

use kosuha606\FormValidationAbstraction\Common\BaseObject;

/**
 * @package kosuha606\FormValidationAbstraction\Form
 */
class VectorForm extends BaseObject implements FormInterface
{
    /**
     * @var array
     */
    public $formNames = [];

    /**
     * @var array
     */
    public $forms = [];

    /**
     * @var array
     */
    public $data = [];

    /**
     * @var array
     */
    public $handlers = [];

    /**
     * @var array
     */
    public $activeForm = [];

    /**
     * @param FormInterface $form
     * @param $handlerClass
     * @param $data
     * @return VectorForm
     */
    public function addForm(FormInterface $form, $handlerClass, $data)
    {
        $this->data[$form->name()] = $this->fillDataByForms($form, $data);
        $this->forms[$form->name()] = $form->toArray();
        $this->handlers[$form->name()] = $handlerClass;
        $this->formNames[$form->name()] = get_class($form);
        $this->activeForm[$form->name()] = 0;

        return $this;
    }

    /**
     * Нужно для того чтобы проверить что данные в массиве данных формы имеют правильные поля
     * @param FormInterface $form
     * @param $data
     * @return array
     */
    private function fillDataByForms(FormInterface $form, $data)
    {
        $resultData = [];
        $formClass = get_class($form);
        foreach ($data as $datum) {
            /** @var FormInterface $form */
            $form = new $formClass;
            $form->setAttributes($datum);
            $resultData[] = $form->toArray();
        }

        return $resultData;
    }

    /**
     * @return string
     */
    public function name()
    {
        return 'vector_form';
    }

    /**
     * @return bool
     */
    public function validate()
    {
        return true;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return [];
    }
}