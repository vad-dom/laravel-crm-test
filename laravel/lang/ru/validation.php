<?php

return [
    'required' => 'Поле обязательно для заполнения',
    'email' => 'Введите корректный email',
    'max' => [
        'string' => 'Максимальная длина поля: :max символов',
        'file' => 'Максимальный размер файла: :max КБ',
    ],
    'regex' => 'Неверный формат поля',
    'array' => 'Поле должно быть массивом',
    'file' => 'Поле должно быть файлом',
    'mimes' => 'Допустимые типы файлов: :values',
    'error' => 'Поле :attribute содержит ошибку.',
    'errors' => 'Поле :attribute содержит :count ошибок.',
    'summary' => [
        'message' => 'Есть ошибки в форме',
        'errors' => 'и ещё :count ошибок',
    ],

    'attributes' => [
        'name' => 'Имя',
        'email' => 'Email',
        'phone_e164' => 'Телефон',
        'subject' => 'Тема',
        'message' => 'Сообщение',
        'attachments' => 'Файлы',
        'attachments.*' => 'Файл',
    ],
];
