<?php
    namespace Cerceau\Data\Base\Field;

	class FieldPoint extends FieldScalar {
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
                if( $values = trim( $value, '()' ) )
                    $value = explode( ',' , $values );
            }

            if( is_array( $value )){
                if( isset( $value['x'], $value['y'] ))
                    $value = array( $value['x'], $value['y'] );
                return $this->setValue( $value );
            }

            unset( $this->Storage[$this->offset] );
            return $this->setValue( null );
        }

        /**
         * @return array
         */
        public function get(){
            $a = $this->Storage->export();
            return $a[$this->offset];
        }

        /**
         * @return array
         */
        public function toScalar(){
            $a = $this->get();
            if( is_null( $a ))
                return null;

            return '('. $a[0] .','. $a[1] .')';
        }
    }
