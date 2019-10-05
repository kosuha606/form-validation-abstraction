FormValidationAbstraction
--
[![Build Status](https://travis-ci.org/kosuha606/form-validation-abstraction.svg?branch=master)](https://travis-ci.org/kosuha606/form-validation-abstraction)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kosuha606/form-validation-abstraction/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/kosuha606/form-validation-abstraction/?branch=master)

An abstract mechanism for performing validation.

## Installation

Installation through the composer:

```bash
$ composer require kosuha606/form-validation-abstraction
```


## Usage

### Validation

Your application forms must implement the interface
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
Next, you need to create a form validation handler.
```php
...
class UserFormHandler implements HandlerInterface
{
    public function handle(FormInterface $form)
    {
        // Save form results
    }
}
```
The following code can be called in the controller to link the form and the handler
```php
...
$userForm = new UserForm([
'name' => 'Eugene',
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
In the variable `$resultTuple` you can get the result of validation
through `getResult` and a list of errors through `getError`.

### Form serialization
It is possible to submit to the service a form filled with data and subsequently
serialize a set of forms to submit to js client.

```php
// name() - returns user_form
$userForm = new UserForm([
'name' => 'Eugene',
'email' => 'kosuha606@gmail.com',
]);
// name() - returns delivery_form
$deliveryForm = new DeliveryForm([
'city' => 'Moscow',
'street' => 'Sadovaya',
]);
FormService::getInstance()->addForm($userForm);
FormService::getInstance()->addForm($deliveryForm);
$jsFormsData = json_encode(FormService::getInstance()->serializeForms());
```
As a result, the following contents will be stored in the variable $ jsFormsData:
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

### Vector form
A vector form is designed to handle an unlimited number of any type of form within a single form.

The following syntax is used to create and serialize a vector form:
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
Further, this data can be manipulated in js code on the client and after making changes
in the data, return them back to the server and process it as follows:
```php
$inputData = $_POST['vector_form'];
$resultTuple = FormService::getInstance()->processForm(
    new VectorForm(),
    new VectorFormHandler(),
    $inputData
);
$handlerResult = $resultTuple->getHandlerResult();
```
As a result, the `$handlerResult` variable will contain the results of each
of the form that was added to the vector form, data about each form will be
refers to the key that was specified in `FormInterface :: name ()`.

### VectorHandlerInterface

This interface will be useful for cases when you need to perform
some operations before executing the form handlers that are added
inside the vector form and after executing these handlers.

For example, if using a vector form, address forms are processed
from the address book, the handler class of the form `AddressForm` can implement
interface VectorHandlerInterface and implement the functions `beforeVectorForm` and
`afterVectorForm` as follows:

```php
class AddressFormHandler implements VectorHandlerInterface
{
    public function beforeVectorForm() {
        Transaction::begin();
        
    }

    public function handle(FormInterface $form) {
        // We process the form, save the data
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

Thus, you can delete old addresses, add new ones in case
some validation errors to roll back changes to the database.
