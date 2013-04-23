<?php
    namespace Cerceau\Data\User;

    class Privileges {
        const
            WATCH_USERS = 1,
            ADD_USERS = 2,
            EDIT_USERS = 3,

            WATCH_CATEGORIES = 11,

            MODERATION = 21
        ;

        private static $privileges;

        public static function names(){
            if(!self::$privileges )
                self::$privileges = \Cerceau\System\Registry::instance()->I18n()->pick( 'privileges', 'all' );

            return self::$privileges;
        }
    }
