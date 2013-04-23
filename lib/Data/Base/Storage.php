<?php
    namespace Cerceau\Data\Base;

    class Storage implements \Cerceau\Data\I\IStorage {

        protected
            $values,
            $subStorages;

        /**
         * @param array $a
         */
        public function __construct( array $a = array() ){
            $this->values = $a;
            $this->subStorages = array();
        }

        public function offsetExists( $offset ){
            return array_key_exists( $offset, $this->values );
        }

        public function offsetGet( $offset ){
            if(!array_key_exists( $offset, $this->values ))
                return null;

            if( $this->isScalar( $this->values[$offset] ))
                return $this->values[$offset];

            if(!array_key_exists( $offset, $this->subStorages ))
                $this->subStorages[$offset] = new Storage( $this->values[$offset] );

            return $this->subStorages[$offset];
        }

        public function offsetSet( $offset, $value ){
            if( $this->isScalar( $value )){
                $this->values[$offset] = $value;
            }
            elseif( is_array( $value ) ){
                if( array_key_exists( $offset, $this->subStorages )){
                    $this->subStorages[$offset]->fetch( $value );
                    $this->values[$offset] = $this->subStorages[$offset]->export();
                }
                else
                    $this->values[$offset] = $value;
            }
            elseif( $value instanceof \Cerceau\Data\I\IStorage ){
                if( array_key_exists( $offset, $this->subStorages )){
                    if( $this->subStorages[$offset] !== $value )
                        $this->subStorages[$offset]->fetch( $value->export());
                }
                else{
                    $this->subStorages[$offset] = $value;
                }
                $this->values[$offset] = $this->subStorages[$offset]->export();
            }
            else
                throw new \UnexpectedValueException( 'Value for offsetSet in '. __CLASS__ .' must be instance of \Cerceau\Data\I\IStorage or not an object' );
        }

        public function offsetUnset( $offset ){
            if(!array_key_exists( $offset, $this->values ))
                return;

            if( is_array( $this->values[$offset] )){
                if( array_key_exists( $offset, $this->subStorages ))
                    unset( $this->subStorages[$offset] );
            }
            unset( $this->values[$offset] );
        }

        /**
         * @param array $a
         * @return \Cerceau\Data\I\IStorage
         */
        final public function fetch( array $a ){
            $this->values = $a;
            return $this;
        }

        /**
         * @param array $a
         * @return \Cerceau\Data\I\IStorage
         */
        final public function merge( array $a ){
            foreach( $a as $offset => $value)
                $this->offsetSet( $offset, $value );
            return $this;
        }

        /**
         * @param \Cerceau\Data\I\IStorage $Storage
         * @return Storage
         * @throws \UnexpectedValueException
         */
        final public function absorb( \Cerceau\Data\I\IStorage $Storage ){
            if(!( $Storage instanceof \Cerceau\Data\I\IStorage ))
                throw new \UnexpectedValueException( __CLASS__ .' can absorb '. __CLASS__ .' only' );

            $keys = $Storage->keys();
            foreach( $keys as $offset )
                $this->offsetSet( $offset, $Storage[$offset] );
            return $this;
        }

        /**
         * @return array
         */
        final public function keys(){
            return array_keys( $this->values );
        }

        /**
         * @return array
         */
        final public function export(){
            foreach( $this->subStorages as $offset => $subStorage ){
                /**
                 * @var \Cerceau\Data\I\IStorage $subStorage
                 */
                $this->values[$offset] = $subStorage->export();
            }
            return $this->values;
        }

        /**
         * @param $value
         * @return bool
         */
        protected function isScalar( $value ){
            return is_null( $value ) || is_scalar( $value );
        }
    }
