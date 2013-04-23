<?php

    namespace Cerceau\Utilities;

    class ImageUrlBuilder implements \Cerceau\Utilities\I\IFileUrlBuilder {

        /**
         * @var \Cerceau\Utilities\I\IFileUrlBuilder
         */
        protected static $Instance;

        protected function __construct(){}

        /**
         * @static
         * @return \Cerceau\Utilities\I\IFileUrlBuilder
         */
        public static function instance(){
            if(!static::$Instance )
                static::$Instance = new static();
            return static::$Instance;
        }

        /**
         * @param \ArrayAccess $data
         * @return string
         * @throws \UnexpectedValueException
         */
        public function url( \ArrayAccess $data ){
            return \Cerceau\System\Registry::instance()->DomainConfig()->main() . $this->relativePath( $data );
        }

        /**
         * @param \ArrayAccess $data
         * @return string
         * @throws \UnexpectedValueException
         */
        public function path( \ArrayAccess $data ){
            return ROOT_WWW . $this->relativePath( $data );
        }

        public function relativePath( \ArrayAccess $data ){
            if(!isset( $data['name'], $data['type'] ))
                throw new \UnexpectedValueException( 'Wrong data to build icon path' );

            return $data::getIconDir()
                .  $data['name']
                .'.'. \Cerceau\Data\Image\Types::getExtension( $data['type'] );
        }
    }