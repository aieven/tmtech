<?php

    namespace Cerceau\Data\Image;

    class Types {
        const
            JPEG = 1,
            PNG = 2
        ;

        private static $extensions = array(
            self::JPEG => 'jpg',
            self::PNG => 'png',
        );
        private static $mimetypes = array(
            self::JPEG => 'image/jpeg',
            self::PNG => 'image/png',
        );

        public static function getMimetype( $id ){
            if( isset( self::$mimetypes[$id] ))
                return self::$mimetypes[$id];
            else
                return false;
        }

        public static function getExtension( $id ){
            if( isset( self::$extensions[$id] ))
                return self::$extensions[$id];
            else
                return false;
        }

        public static function getIdByMimetype( $mimetype ){
            return array_search( $mimetype, self::$mimetypes );
        }

        public static function getIdByExtension( $extension ){
            return array_search( $extension, self::$extensions );
        }

        public static function mimetypes(){
            return self::$mimetypes;
        }
    }
