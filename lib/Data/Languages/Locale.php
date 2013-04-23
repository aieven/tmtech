<?php

    namespace Cerceau\Data\Languages;

    class Locale {
        const
            EN_US = 0,
            RU_RU = 1;

        private static $locale = array(
            self::EN_US => 'en_US',
            self::RU_RU => 'ru_RU'
        );

        public static function availableLocale(){
            return self::$locale;
        }
    }
