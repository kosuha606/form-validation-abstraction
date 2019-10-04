FormValidationAbstraction
--

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

TODO дописать про векторные формы