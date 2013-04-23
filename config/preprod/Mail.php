<?php
    namespace Cerceau\Config;

    class Mail extends \Cerceau\Mail\Config {
        protected static $settings = array(
            'default' => array(
                'type'   => 'smtp',
                'site'   =>	'ClassyGram',
                'from'   =>	'ClassyGram',
                'sender' =>	'admin@instarepost.com',
                'host' =>	'smtp.gmail.com',
                'port' =>	'465',
                'user' =>	'admin@instarepost.com',
                'pwd' =>	'!Su%%life',
            ),
        );
    }
