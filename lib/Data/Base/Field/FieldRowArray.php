<?php
    namespace Cerceau\Data\Base\Field;

	class FieldRowArray extends FieldSerialized {
        /**
         * @var \Cerceau\Data\Base\RowsStorage
         */
        private $Rows;

        /**
         * @var string
         */
        private $className;

        /**
         * @var array
         */
        private $classNames;

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
                $this->Rows = null;

            elseif( is_array( $value )){
                $this->initializeRows();
                foreach( $value as $k => $v )
                    $this->Rows[$k] = $v;
            }

            else{
                throw new \UnexpectedValueException( 'Wrong data type for field "'. $this->offset .'"' );
            }

            $this->changed = true;
            unset( $this->Storage[$this->offset] );
            return $this->Storage[$this->offset] = $this->Rows;
        }

        protected function setClass( $className ){
            $this->className = $className;
        }

        protected function setClasses( $classNames ){
            if(!is_array( $classNames ))
                throw new \UnexpectedValueException( 'Classes property for field "'. $this->offset .'" must be an array' );

            $this->classNames = $classNames;
        }

        /**
         * @return \Cerceau\Data\I\IStorage
         */
        public function get(){
            return $this->Rows;
        }

        /**
         * @return bool
         */
        public function isChanged(){
            if( $this->changed )
                return true;

            if( $this->Rows ){
                foreach( $this->Rows->keys() as $k )
                    if( $this->Rows[$k]->isChanged())
                        return true;
            }
            return false;
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

        /**
         * @return string
         */
        protected function initializeRows(){
            $this->Rows = new \Cerceau\Data\Base\RowsStorage();
            if( $this->className )
                $this->Rows->setClass( $this->className );
            elseif( $this->classNames )
                $this->Rows->setClasses( $this->classNames );
        }
    }
