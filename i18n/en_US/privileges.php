<?php
$list = array(
    'all' => array(
        \Cerceau\Data\User\Privileges::WATCH_USERS => 'User can watch all users',
        \Cerceau\Data\User\Privileges::ADD_USERS => 'User can add new users',
        \Cerceau\Data\User\Privileges::EDIT_USERS => 'User can edit any user',

        \Cerceau\Data\User\Privileges::WATCH_CATEGORIES => 'User can watch categories',

        \Cerceau\Data\User\Privileges::MODERATION => 'User can moderate ',
    ),
    'groups' => array(
        \Cerceau\Data\User\Privileges::WATCH_USERS => 'Users',
        \Cerceau\Data\User\Privileges::MODERATION => 'Moderation',
    ),
);