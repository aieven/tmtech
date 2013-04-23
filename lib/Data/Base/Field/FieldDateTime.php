<?php
    namespace Cerceau\Data\Base\Field;

	class FieldDateTime extends FieldScalar {
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

            if( is_numeric( $value ))
                return $this->setValue( intval( $value ));

            if( is_string( $value ))
                return $this->setValue( strtotime( $value ));

            $this->setValue( null );
            return null;
        }

        /**
         * @return string
         */
        public function toScalar(){
            $value = $this->get();
            if( is_null( $value ))
                return null;
            return date( 'Y-m-d H:i:s', $value );
        }
    }
