<?php
    namespace Cerceau\Data\Base\Field;

	class FieldRow extends FieldSerialized {
        /**
         * @var \Cerceau\Data\I\IDataRow
         */
        private $Row;

        /**
         * @var string
         */
        private $className;

        /**
         * @param $value
         * @param bool $fetch
         * @return null|string
         * @throws \UnexpectedValueException
         */
        public function set( $value, $fetch = true ){
            if( $fetch && $this->readonly )
                return $this->setValue( null );

            if( $this->const ){
                $oldValue = $this->Storage[$this->offset];
                if( null !== $oldValue )
                    return $oldValue;
            }

            unset( $this->Storage[$this->offset] );

            if( is_string( $value ))
                $value = unserialize( $value );

            if( is_null( $value ))
                $this->Row = null;

            elseif( is_a( $value, '\\Cerceau\\Data\\I\\IDataRow' ))
                $this->Row = $value;

            else{
                if(!is_a( $this->Row, '\\Cerceau\\Data\\I\\IDataRow' )){
                    if( $this->className ){
                        $this->Row = new $this->className();
                    }
                    else
                        throw new \UnexpectedValueException( 'Row is not set for field "'. $this->offset .'"' );
                }

                if( is_a( $value, '\\Cerceau\\Data\\I\\IStorage' ))
                    /**
                     * @var \Cerceau\Data\I\IStorage $value
                     */
                    $this->Row->set( $value );

                elseif( is_array( $value ))
                    $this->Row->fetch( $value );

                else
                    throw new \UnexpectedValueException( 'Wrong data type for field "'. $this->offset .'"' );
            }

            return $this->setValue( $this->Row );
        }

        protected function setClass( $className ){
            $className = '\\Cerceau\\Data\\'. $className;
            if(!class_exists( $className, true ))
                throw new \UnexpectedValueException( 'Class "'. $className .'" not implemented' );

            $this->className = $className;
            $this->validators['isA'] = $className;
        }

        protected function validateIsA( $className ){
            if( is_null( $this->Row ))
                return true;
            if(!is_a( $this->Row, $className ))
                return false;
            try {
                $this->Row->validate();
            }
            catch( \Cerceau\Exception\FieldValidation $E ){
                throw new \Cerceau\Exception\FieldValidation( $this->validationPrefix . $E->getMessage());
            }
            return true;
        }

        /**
         * @return \Cerceau\Data\I\IDataRow
         */
        public function get(){
            return $this->Row;
        }

        /**
         * @return bool
         */
        public function isChanged(){
            return $this->changed || ( $this->Row && $this->Row->isChanged());
        }

        /**
         * @return string
         */
        public function toScalar(){
            $value = $this->get();
            if( is_null( $value ) )
                return null;
            return serialize( $value->export());
        }
    }
