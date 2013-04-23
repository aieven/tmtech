<?php
    namespace Cerceau\Resource;

    class UrlBuilder implements \Cerceau\Utilities\I\IFileUrlBuilder {

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
         * @param array $data
         * @return string
         * @throws \UnexpectedValueException
         */
        public function url( array $data ){
            return \Cerceau\System\Registry::instance()->DomainConfig()->spot( 'resource', Manager::instance()->getSpotId( $data['resource_id'] ))
                .'/'. $this->relativePath( $data );
        }

        /**
         * @param array $data
         * @return string
         * @throws \UnexpectedValueException
         */
        public function path( array $data ){
            return $this->relativePath( $data );
        }

        private function relativePath( array $data ){
            if(!isset( $data['resource_id'], $data['type'], $data['name'] ))
                throw new \UnexpectedValueException( 'Wrong data to build resource path' );

            $subDir = dechex( $data['resource_id'] % 10000 ) .'/';
            $subSpot = intval( $data['resource_id'] / 1000 ) .'/';

            return Types::getPrefix( $data['type'] ) . $subSpot . $subDir . $data['name'] . Types::getExtension( $data['type'] );
        }
    }