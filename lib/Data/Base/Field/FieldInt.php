<?php
    namespace Cerceau\Data\Base\Field;

	class FieldInt extends FieldScalar {
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

            if(!$fetch && $value === 't' ) // hack for postgresql boolean TRUE
                return $this->setValue( 1 );

            if( is_bool( $value ))
                return $this->setValue( $value ? 1 : 0 );

            if( is_numeric( $value ))
                return $this->setValue( intval( $value ));

            $this->setValue( null );
            return null;
        }
    }
