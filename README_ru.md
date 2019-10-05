FormValidationAbstraction
--
[![Build Status](https://travis-ci.org/kosuha606/form-validation-abstraction.svg?branch=master)](https://travis-ci.org/kosuha606/form-validation-abstraction)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kosuha606/form-validation-abstraction/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/kosuha606/form-validation-abstraction/?branch=master)

Абстрактный механизм для выполнения валидации.

## Установка

Установка через композер:

```bash
$ composer require kosuha606/form-validation-abstraction
```


## Использование

### Валидация

Формы вашего приложения должны реализоваывать интерфейс
`kosuha606\FormValidationAbstraction\Form\FormInterface`

```php
...
class UserForm implements FormInterface
{
    public $name;
    
    public $email;

    ...
}

```
Далее необходимо создать обработчик успешной валидации формы
```php
...
class UserFormHandler implements HandlerInterface
{
    public function handle(FormInterface $form)
    {
        // Сохраняем результаты формы
    }
}
```
Следующий код может быть вызван в контроллере для связи формы и обработчика
```php
...
$userForm = new UserForm([
'name' => 'Евгений',
'email' => 'kosuha606@gmail.com',
]);
$postUnsafeData = $_POST['user_form'];
/** @var FormServiceTupleInterface */
$resultTuple = FormService::getInstance()
    ->processForm(
        $userForm,
        UserFormHandler::class,
        $postUnsafeData
    )
;
```
В переменной `$resultTuple` можно получить результат выполнения валидации
через `getResult` и список ошибок через `getError`.

### Сериализация форм
Есть возможность передать сервису формы, заполненный данными и в последствии
сериализовать набор форм для передачи в js клиенту.

```php
// name() - возвращает user_form
$userForm = new UserForm([
'name' => 'Евгений',
'email' => 'kosuha606@gmail.com',
]);
// name() - возвращает delivery_form
$deliveryForm = new DeliveryForm([
'city' => 'Москва',
'street' => 'Садовая',
]);
FormService::getInstance()->addForm($userForm);
FormService::getInstance()->addForm($deliveryForm);
$jsFormsData = json_encode(FormService::getInstance()->serializeForms());
```
В итоге в переменной `$jsFormsData` будет храниться такое содержимое:
```json
{
  "user_form": {
    "name": "",
    "email": ""
  },
  "delivery_form": {
    "city": "",
    "street": ""
  },
}
```

### Векторная форма
Векторная форма предназначена для обработки неограниченного количества любых типов форм внутри одной формы.

Для создания и сериализации векторной формы используется такой синтаксис:
```php
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
```
Далее этими данными можно манипулировать в js коде на клиенте и после внесения изменений
в данные вернуть их обратно на сервер и обработать следующим образом:
```php
$inputData = $_POST['vector_form'];
$resultTuple = FormService::getInstance()->processForm(
    new VectorForm(),
    new VectorFormHandler(),
    $inputData
);
$handlerResult = $resultTuple->getHandlerResult();
```
В результате в переменно `$handlerResult` будут результаты валидации каждой
формы, которая была добавлена в векторную форму, данные о каждой форме будут 
относится к ключу, который был указан в `FormInterface::name()`.

### VectorHandlerInterface

Этот интерфейс будет полезен для случаев когда нужно выполнить 
какие то операции перед выполнение обработчиков форм, которые добавлены
внутрь векторной формы и после выполнения этих обработчиков.

Например, если с помощью векторной формы обрабатываются формы адресов
из адресной книги, то класс-обработчик формы `AddressForm` может реализовать
интерфейс VectorHandlerInterface и реализовать функции `beforeVectorForm` и
`afterVectorForm` следующим образом:

```php
class AddressFormHandler implements VectorHandlerInterface
{
    public function beforeVectorForm() {
        Transaction::begin();
        
    }

    public function handle(FormInterface $form) {
        // Обрабатываем форму, сохраняем данные
    }

    public function afterVectorForm($result) {
        if (!$result['result']) {
            Transaction::rollback();
            return;
        }
        Transaction::commit();
    }
}
```

Таким образом можно удалить старые адреса, добавить новые и в случае
каких то ошибок валидации откатить изменения в БД.
