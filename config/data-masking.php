<?php

/**
|--------------------------------------------------------------------------
| Configure the format of each table field
|--------------------------------------------------------------------------
|
| 'name' => ''          nothing to do
| 'name' => '*:1-'      Tom => ***  Replace all characters
| 'name' => '0:2-3'     William => W000iam
| 'name' => '0:3-5'     Tom => To000
| 'phone' => '*:4-7'    18666666666 => 186****6666
| 'phone' => '123:4-'   18666666666 => 18612312312
| 'phone' => '*:1~2'    18666666666 => 1********66  Keep the first and end character
| 'email' => 'email:3-' production@google.com => pr********@google.com  Replace only the characters before @
| You can customize any character
|
| You can also use faker to replace column
| 'address' => 'faker:address'
| 'date' => "faker:date($format = 'Y-m-d', $max = 'now')"
| Faker wiki https://github.com/fzaninotto/Faker
|
| If you want to use chinese, open config/app.php add 'faker_locale' => 'zh_CN',
|
 */


return [
    'students' => [
        'name' => 'faker:name',
        'email' => 'email:9-',
        'phone' => '*:4-7',
        'cpf' => '*:4-7',
        'rg' => '*:4-7',
        'expedient_body' => '',
        'nationality' => '',
        'naturalness' => 'faker:city',
        'name_mother' => 'faker:name',
        'birth_date' => '',
        'gender' => '',
        'deleted_at' => '',
        'created_at' => '',
        'updated_at' => '',
        'image' => '',
    ],
    'teams' => [
        'name' => 'faker:catchphrase',
        'start_date' => '',
        'end_date' => '',
        'polo_id' => '',
        'grid_id' => '',
        'deleted_at' => '',
        'created_at' => '',
        'updated_at' => '',
    ],
    'polos' => [
        'name' => 'faker:country'
    ],
    'grids' => [
        'name' => 'faker:company'
    ],
    'users' => [
        'name' => 'faker:name',
        'email' => 'faker:email'
    ],
    'polos' => [
        'email' => 'faker:email',
        'responsible' => 'faker:name',
        'name' => 'faker:name',
        'city' => 'faker:city',
        'address' => 'faker:address',
        'phone' => '*:4-7',
    ]
];
