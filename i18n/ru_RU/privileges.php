<?php
$list = array(
    'all' => array(
        \Cerceau\Data\User\Privileges::WATCH_USERS => 'Просмотр пользователей',
        \Cerceau\Data\User\Privileges::ADD_USERS => 'Добавление пользователей',
        \Cerceau\Data\User\Privileges::EDIT_USERS => 'Редактирование пользователей',

        \Cerceau\Data\User\Privileges::WATCH_CATEGORIES => 'Просмотр категорий',

        \Cerceau\Data\User\Privileges::MODERATION => 'Модерация',
    ),
    'groups' => array(
        \Cerceau\Data\User\Privileges::WATCH_USERS => 'Пользователи',
        \Cerceau\Data\User\Privileges::MODERATION => 'Модерация',
    ),
);