<?php
    namespace Cerceau\Data\Base\Field;

	class FieldJsonStorage extends FieldScalar {
        /**
         * @var \Cerceau\Data\I\IStorage
         */
        private $FieldStorage;

        /**
         * @param $value
         * @param bool $fetch
         * @return \Cerceau\Data\I\IDataRow|mixed|null|string
         * @throws \UnexpectedValueException
         */
        public function set( $value, $fetch = true ){
            if( $fetch && $this->readonly )
                return null;

            if( $this->const ){
                $oldValue = $this->get();
                if( null !== $oldValue )
                    return $oldValue;
            }

            unset( $this->Storage[$this->offset] );

            if( is_string( $value ))
                $value = json_decode( $value, true );

            if( is_null( $value ))
                $this->FieldStorage = null;

            elseif( is_a( $value, '\\Cerceau\\Data\\I\\IStorage' ))
                $this->FieldStorage = $value;

            else{
                if(!$this->FieldStorage )
                    $this->FieldStorage = new \Cerceau\Data\Base\Storage();

                if( is_array( $value ))
                    $this->FieldStorage->fetch( $value );

                else
                    throw new \UnexpectedValueException( 'Wrong data type for field "'. $this->offset .'"'. print_r($value,true) );
            }

            return $this->setValue( $this->FieldStorage );
        }

        /**
         * @return \Cerceau\Data\I\IDataRow
         */
        public function get(){
            return $this->FieldStorage;
        }

        /**
         * @return string
         */
        public function toScalar(){
            $value = $this->get();
            if( is_null( $value ) )
                return null;
            return json_encode( $value->export());
        }
    }
