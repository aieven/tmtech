<?php
    namespace Cerceau\Data\Base\Field;

	class FieldBoolean extends FieldScalar {
        /**
         * @param $value
         * @param bool $fetch
         * @return int|null
         */
        public function set( $value, $fetch = true ){
            if( $fetch && $this->readonly )
                return null;

            if( $this->const ){
                $oldValue = $this->get();
                if( null !== $oldValue )
                    return $oldValue;
            }

            if( is_scalar( $value )){
                $value = strtolower( $value );
                if( in_array( $value, array( 'f', 'false', 'no' ))) // hack for scalar booleans
                    return $this->setValue( false );
            }

            if(!is_null( $value ))
                return $this->setValue( !!$value );

            $this->setValue( null );
            return null;
        }

        public function toScalar(){
            return $this->get() ? 't' : 'f';
        }
    }
