<?php
$list = array(
    'name' => array(
        'notEmpty' => 'Необходимо название!',
    ),
    'image' => array(
        'unexpectedType' => 'Неверный формат изображения.',
        'notEmpty'      => 'Изображение отсутствует.',
        'upload' => array(
            'notEmpty'      => 'Изображение отсутствует.',
            'wrongDimensions' => 'Загруженное изображение имеет неправильные размеры.',
            'uploadError'   => 'Ошибка загрузки изображения.',
            'unexpectedType' => 'Неверный формат изображения.',
        ),
    ),
    'public' => array(
        'alreadyExists' => 'Паблик уже существует в системе',
    ),

);