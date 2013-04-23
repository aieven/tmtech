<?php
    namespace Cerceau\Data\Base\Field;

	class FieldEnum extends FieldScalar implements \Cerceau\Data\I\IEnum {
        protected $types;

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

            if( array_key_exists( $value, $this->types )){
                if( is_numeric( $value ))
                    $value = intval( $value );
                return $this->setValue( $value );
            }

            $this->setValue( null );
            return null;
        }

        public function setTypes( $value ){
            $this->types = $value;
        }

        /**
         * @return array
         */
        public function options(){
            return $this->types;
        }

        /**
         * @param int $value
         * @return string
         */
        public function name( $value ){
            return isset( $this->types[$value] ) ? $this->types[$value] : null;
        }

        /**
         * @param string $name
         * @return int
         */
        public function searchValue( $name ){
            return array_search( $name, $this->types );
        }
    }
