<?php
    namespace Cerceau\Data\Base\Field;

	class FieldSerialized extends FieldScalar {
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

            if(!$fetch && is_string( $value ))
                $value = unserialize( $value );

            return $this->setValue( $value );
        }

        /**
         * @return string
         */
        public function get(){
            $a = $this->Storage->export();
            return $a[$this->offset];
        }

        /**
         * @return string
         */
        public function toScalar(){
            $value = $this->get();
            if( is_null( $value ))
                return null;
            return serialize( $value );
        }

    }
