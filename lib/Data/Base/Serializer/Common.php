<?php

    namespace Cerceau\Data\Base\Serializer;

    class Common implements \Cerceau\Data\I\ISerializer {

        /**
         * return string
         */
        public function serialize( $data ){
            return serialize( $data );
        }

        /**
         * @param string $string
         * @return mixed
         */
        public function unserialize( $string ){
            return unserialize( $string );
        }

        /**
         * @param array $options
         */
        public function options( array $options ){}
    }
