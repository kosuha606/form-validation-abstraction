<?php

class TestData
{
    public static $data = [
        'user_form' => [
            'name' => 'Eugene',
            'email' => 'kosuha606@gmail.com',
        ],
        'delivery_form' => [
            'city' => 'Moscow',
            'street' => 'Sadovaya',
        ],
        'vector_form' => [
            'formNames' => [
                'user_form' => 'UserForm',
                'delivery_form' => 'DeliveryForm'
            ],
            'forms' => [
                'user_form' => [
                    'name' => null,
                    'email' => null,
                ],
                'delivery_form' => [
                    'city' => null,
                    'street' => null,
                ],
            ],
            'data' => [
                'user_form' => [
                    [
                        'name' => 'Eugene',
                        'email' => null, // Это значение должно выдать ошибку в веторной форме
                    ]
                ],
                'delivery_form' => [
                    [
                        'city' => 'Moscow',
                        'street' => 'Sadovaya',
                    ]
                ],
            ],
            'handlers' => [
                'user_form' => 'UserHandler',
                'delivery_form' => 'DeliveryHandler'
            ],
            'activeForm' => [
                'user_form' => 0,
                'delivery_form' => 0
            ],
        ]
    ];
}