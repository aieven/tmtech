<?php
    namespace Cerceau\Config;

    class Assets extends \Cerceau\View\Config {
        protected static $css = array(
            'bootstrap.min',
            'datepicker',
            'jcrop.min',
            'main',
        );
        protected static $js = array(
            'jq/jquery.min',
            'jq/jcrop.min',
            'jq/jquery.form',
            'jq/jquery.cookie',
            'jq/jquery.tokeninput',
            'jq/jquery-ui-1.9.1.datepicker.min',

            'bootstrap/bootstrap.min',
            'bootstrap/bootstrap-datepicker',

            'objx/objx-2.3.6',
            'objx/event',
            'objx/property',
            'objx/class',

            'i18n/i18n',
            'i18n/ru_RU',

            'std/module',
            'std/Globals',
            'std/Page',
            'std/Template',
            'std/Form.Field',
            'std/Form',

            'page/Router',
            'page/Admin/Auth',
            'page/Admin/Chart',
            'page/Admin/Users',
            'page/Admin/SmmBot',
            'page/Admin/Bots',
            'page/Admin/Snapshot',
            'page/Admin/Gallery',
            'page/Admin/Gallery.Publics',
            'page/Admin/PeopleAndBrands',
            'page/Admin/PeopleAndBrands.Publics',
        );
    }
