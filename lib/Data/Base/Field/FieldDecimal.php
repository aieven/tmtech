<?php
    namespace Cerceau\Data\Base\Field;

	class FieldDecimal extends FieldScalar {
        private $precision;

        /**
         * @param $value
         * @param bool $fetch
         * @return float|null
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
                return $this->setValue( $this->precision ? round( floatval( $value ), $this->precision ) : floatval( $value ));

            $this->setValue( null );
            return null;
        }

        public function setPrecision( $value ){
            $this->precision = $value;
        }
    }
