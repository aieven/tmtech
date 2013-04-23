<?php
    namespace Cerceau\Data\Base\Field;

	class FieldFieldArray extends FieldSerialized {
        /**
         * @var array
         */
        private $fieldsOptions;

        /**
         * @var \Cerceau\Data\Base\CustomRow
         */
        private $Row;

        /**
         * @param $value
         * @param bool $fetch
         * @return null|string
         * @throws \UnexpectedValueException
         */
        public function set( $value, $fetch = true ){
            if( $fetch && $this->readonly )
                return $this->setValue( null );

            if( is_string( $value ))
                $value = unserialize( $value );

            if( is_null( $value ))
                $this->Row = null;

            elseif( is_array( $value )){
                $options = array();
                foreach( $value as $offset => $v ){
                    $options[$offset] = $this->fieldsOptions;
                }
                $this->Storage[$this->offset] = array();
                $this->Row = new \Cerceau\Data\Base\CustomRow( $this->Storage[$this->offset], $options );
                foreach( $value as $offset => $v ){
                    $this->Row[$offset] = $v;
                }
            }

            else{
                throw new \UnexpectedValueException( 'Wrong data type for field "'. $this->offset .'"' );
            }

            return $this->setValue( $this->Row );
        }

        protected function setFieldsOptions( array $options ){
            $this->fieldsOptions = $options;
        }

        /**
         * @return \Cerceau\Data\I\IStorage
         */
        public function get(){
            return $this->Row;
        }

        /**
         * @return bool
         */
        public function isChanged(){
            if( $this->changed )
                return true;

            if( $this->Row )
                return $this->Row->isChanged();
            else
                return false;
        }

        /**
         * Validate row fields
         *
         * @return bool
         */
        public function validateFields(){
            if( $this->Row )
                $this->Row->validate();
            return true;
        }

        /**
         * Verify that all keys in a row are specified
         *
         * @param array $keys
         * @return bool
         */
        public function validateKeys( array $keys ){
            if( $this->Row )
                return !array_diff( $this->Row->keys(), $keys );
            else
                return true;
        }

        /**
         * Verify that all specified keys are present in a row
         *
         * @param array $keys
         * @return bool
         */
        public function validatePreciseKeys( array $keys ){
            if( $this->Row ){
                return !array_diff( $this->Row->keys(), $keys ) && !array_diff( $keys, $this->Row->keys());
            }
            else
                return true;
        }

        /**
         * @return string
         */
        public function toScalar(){
            $value = $this->get();
            if( is_null( $value ))
                return null;
            return serialize( $value->export());
        }
    }
