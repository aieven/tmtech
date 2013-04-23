<?php

    namespace Cerceau\Resource;

    class Types {
        const
            // content photos
            PHOTO = 1,
            PHOTO_THUMB = 2,

            // userpics
            PHOTO_PREVIEW = 3,
            AVATAR = 4
        ;

        private static $extensions = array(
            self::PHOTO => '.jpg',
            self::PHOTO_THUMB => '_s.jpg',

            self::PHOTO_PREVIEW => '_m.jpg',
            self::AVATAR => '_a.jpg',
        );
        private static $prefixes = array(
            self::PHOTO => 'ph',
            self::PHOTO_THUMB => 'ph',

            self::PHOTO_PREVIEW => 'ph',
            self::AVATAR => 'ph',
        );

        public static function getExtension( $id ){
            if( isset( self::$extensions[$id] ))
                return self::$extensions[$id];
            else
                return false;
        }

        public static function getPrefix( $id ){
            if( isset( self::$prefixes[$id] ))
                return self::$prefixes[$id];
            else
                return false;
        }
    }
