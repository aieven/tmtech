<?php
    namespace Cerceau\Data\Base\Field;

	class FieldScalar extends \Cerceau\Data\Base\Field {
        protected
            $readonly = false,
            $const = false;

        /**
         * @param $value
         * @param bool $fetch
         * @return string|null
         */
        public function set( $value, $fetch = true ){
            if( $fetch && $this->readonly )
                return null;

            if( $this->const ){
                $oldValue = $this->get();
                if( null !== $oldValue )
                    return $oldValue;
            }

            if( is_scalar( $value ) )
                return $this->setValue( strval( $value ));

            $this->setValue( null );
            return null;
        }

        protected function setReadonly(){
            $this->readonly = true;
        }

        protected function setConst(){
            $this->const = true;
        }

        protected function validateNotNull(){
            return null !== $this->get();
        }

        protected function validateNotEmpty(){
            $value = $this->get();
            return !empty($value);
        }
    }
