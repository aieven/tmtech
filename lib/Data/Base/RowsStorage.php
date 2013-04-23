<?php
    namespace Cerceau\Data\Base;

    class RowsStorage extends Storage implements \IteratorAggregate {

        /**
         * @var string
         */
        private $className;

        /**
         * @var array
         */
        private $classNames;

        public function getIterator(){
            return new \ArrayIterator( $this->subStorages );
        }

        public function setClass( $className ){
            $classNameCheck = '\\Cerceau\\Data\\'. $className;
            if(!class_exists( $classNameCheck, true ))
                throw new \UnexpectedValueException( 'Class "'. $classNameCheck .'" not implemented' );

            $this->className = $className;
        }

        public function setClasses( array $classNames ){
            foreach( $classNames as $className ){
                $className = '\\Cerceau\\Data\\'. $className;
                if(!class_exists( $className, true ))
                    throw new \UnexpectedValueException( 'Class "'. $className .'" not implemented' );
            }

            $this->classNames = $classNames;
        }

        public function offsetSet( $offset, $value ){
            // expected class
            if( $this->className ){
                $className = '\\Cerceau\\Data\\'. $this->className;
            }
            elseif( $this->classNames ){
                if(!isset( $this->classNames[$offset] ))
                    throw new \UnexpectedValueException( 'Class name for offset "'. $offset .'" is not defined in '. __CLASS__ );
                $className = '\\Cerceau\\Data\\'. $this->classNames[$offset];
            }
            else {
                $className = false;
            }
            // got row
            if( is_a( $value, '\\Cerceau\\Data\\I\\IDataRow' )){
                // expect class?
                if( $className && !is_a( $value, $className ))
                    throw new \UnexpectedValueException( 'Value for offsetSet in '. __CLASS__ .' must be an instance of the defined class name "'. $className .'"' );

                $this->subStorages[$offset] = $value;
                $this->values[$offset] = $this->subStorages[$offset]->export();
                return;
            }
            
            // not row
            if( // row is not set yet for offset
                empty( $this->subStorages[$offset] ) ||
                !is_a( $this->subStorages[$offset], '\\Cerceau\\Data\\I\\IDataRow' )
            ){
                if( $className ){
                    $this->subStorages[$offset] = new $className();
                }
                else
                    throw new \UnexpectedValueException( 'Row is not set in '. __CLASS__ .' for offset "'. $offset .'"' );
            }

            // row is set and we've got array/storage
            if( is_a( $value, '\\Cerceau\\Data\\I\\IStorage' ))
                $this->subStorages[$offset]->set( $value );
            elseif( is_array( $value ))
                $this->subStorages[$offset]->fetch( $value );
            else
                throw new \UnexpectedValueException( 'Wrong data type in '. __CLASS__ .' for offset "'. $offset .'"' );

            $this->values[$offset] = $this->subStorages[$offset]->export();
        }

    }
