<?php

class UserForm extends AbstractForm
{
    public $name;

    public $email;

    public function name()
    {
        return 'user_form';
    }

    public function validate()
    {
        $result = true;
        if (!$this->name) {
            $result = false;
            $this->errors[] = ['Заполните имя'];
        }
        if (!$this->email) {
            $result = false;
            $this->errors[] = ['Заполните email'];
        }

        return $result;
    }
}