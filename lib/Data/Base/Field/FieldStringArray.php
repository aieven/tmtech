<?php
    namespace Cerceau\Data\Base\Field;

    class FieldStringArray extends FieldSerializedArray {
        /**
         * @param $value
         * @param bool $fetch
         * @return string|null
         */
        public function set( $value, $fetch = true ){
            if( $fetch && $this->readonly )
                return null;

            if( $this->const ){
                $oldValue = $this->Storage[$this->offset];
                if( null !== $oldValue )
                    return $oldValue;
            }

            if( is_string( $value )){
                $value = trim( $value, '{}' );
                if( $value )
                    $value = explode( ',', $value );
                else
                    $value = array();
            }

            if( is_array( $value ))
                return $this->setValue( $value );

            unset( $this->Storage[$this->offset] );
            return $this->setValue( null );
        }

        /**
         * @return array
         */
        public function toScalar(){
            $a = $this->get();
            if( is_null( $a ))
                return null;

            foreach( $a as &$v ){
                if(!is_numeric( $v ))
                    $v = '"'. $v .'"';
            }
            return '{'. implode( ',', $a ) .'}';
        }
    }
