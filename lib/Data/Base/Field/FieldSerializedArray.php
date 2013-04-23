<?php
    namespace Cerceau\Data\Base\Field;

	class FieldSerializedArray extends FieldSerialized {
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

            if( is_string( $value ))
                $value = unserialize( $value );

            if( is_array( $value ))
                return $this->setValue( $value );

            unset( $this->Storage[$this->offset] );
            return $this->setValue( null );
        }
    }
