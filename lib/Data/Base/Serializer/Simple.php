<?php

    namespace Cerceau\Data\Base\Serializer;

    class Simple implements \Cerceau\Data\I\ISerializer {

        protected $fields = array();

        /**
         * return string
         */
        public function serialize( $data ){
            $implode = array();
            foreach( $this->fields as $field )
                $implode[] = $data[$field];
            return implode( ',', $implode );
        }

        /**
         * @param string $string
         * @return mixed
         */
        public function unserialize( $string ){
            return array_combine( $this->fields, explode( ',', $string ));
        }

        /**
         * @param array $options
         * @throws \UnexpectedValueException
         */
        public function options( array $options ){
            if( isset( $options['fields'] ) && is_array( $options['fields'] ))
                $this->fields = $options['fields'];
            else
                throw new \UnexpectedValueException( 'Wrong options for '.__NAMESPACE__.'\\'.__CLASS__ );
        }
    }
