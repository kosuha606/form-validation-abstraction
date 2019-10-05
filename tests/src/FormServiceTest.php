<?php

namespace kosuha606\FormValidationAbstraction\Service;


use DeliveryForm;
use kosuha606\FormValidationAbstraction\Form\VectorForm;
use kosuha606\FormValidationAbstraction\Handler\VectorFormHandler;
use PHPUnit\Framework\TestCase;
use UserForm;

class FormServiceTest extends TestCase
{
    /**
     * Очищаем все сохраненные  формы перед каждым тестом
     */
    protected function setUp()
    {
        parent::setUp();
        FormService::getInstance()->clearForms();
    }

    public function testGetInstance()
    {
        $formService = FormService::getInstance();
        $this->assertInstanceOf(FormService::class, $formService);
    }

    /**
     * Тест сериализации форм
     */
    public function testSerializeForms()
    {
        $userForm = new UserForm([
            'name' => 'Eugene',
            'email' => 'kosuha606@gmail.com',
        ]);
        $deliveryForm = new DeliveryForm([
            'city' => 'Moscow',
            'street' => 'Sadovaya',
        ]);
        FormService::getInstance()
            ->addForm($userForm)
            ->addForm($deliveryForm)
        ;
        $jsFormsData = json_encode(FormService::getInstance()->serializeForms());
        $this->assertEquals(
            '{"user_form":{"name":"Eugene","email":"kosuha606@gmail.com"},"delivery_form":{"city":"Moscow","street":"Sadovaya"}}',
            $jsFormsData
        );
    }

    /**
     * Тест сериализации векторной формы
     */
    public function testSerializeVectorForm()
    {
        $vectorForm = new VectorForm();
        $vectorForm->addForm(new UserForm(), \UserHandler::class, [
            ['name' => 'One', 'email' => 'email@email.com']
        ]);
        $vectorForm->addForm(new DeliveryForm(), \DeliveryHandler::class, [
            ['city' => 'One', 'street' => 'email@email.com']
        ]);
        FormService::getInstance()
            ->addForm($vectorForm)
        ;
        $jsFormsData = json_encode(FormService::getInstance()->serializeForms());
        $this->assertEquals(
            '{"vector_form":{"formNames":{"user_form":"UserForm","delivery_form":"DeliveryForm"},"forms":{"user_form":{"name":null,"email":null},"delivery_form":{"city":null,"street":null}},"data":{"user_form":[{"name":"One","email":"email@email.com"}],"delivery_form":[{"city":"One","street":"email@email.com"}]},"handlers":{"user_form":"UserHandler","delivery_form":"DeliveryHandler"},"activeForm":{"user_form":0,"delivery_form":0}}}',
            $jsFormsData
        );
    }

    /**
     * Выполнение обработки обычных форм
     * @throws \Assert\AssertionFailedException
     * @throws \ReflectionException
     */
    public function testProcessForm()
    {
        $inputData = [
            'name' => null,
            'email' => 'email@hello.com'
        ];
        $resultTuple = FormService::getInstance()->processForm(
            new UserForm(),
            new \UserHandler(),
            $inputData
        );
        $this->assertEquals($resultTuple->getError(), [
            'user_form' => [
                ['Заполните имя']
            ]
        ]);
    }

    /**
     * Выполнение обработки векторной формы
     * @throws \Assert\AssertionFailedException
     * @throws \ReflectionException
     */
    public function testProcessVectorForm()
    {
        $inputData = \TestData::$data['vector_form'];
        $resultTuple = FormService::getInstance()->processForm(
            new VectorForm(),
            new VectorFormHandler(),
            $inputData
        );
        $handlerResult = $resultTuple->getHandlerResult();
        $this->assertEquals([
            'user_form' => [
                ['Заполните email']
            ]
        ], $handlerResult['user_form']['error'][0]);
        $this->assertTrue($handlerResult['delivery_form']['result']);
    }
}
