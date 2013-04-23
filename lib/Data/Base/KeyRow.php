<?php
    namespace Cerceau\Data\Base;

	abstract class KeyRow extends Row {
        /**
         * @var string
         */
        protected static $modelName = 'Redis\\Authorizer';

        /**
         * @var string
         */
        protected static $name = null;

        /**
         * @var string
         */
        protected static $db = null;

        /**
         * @var string
         */
        protected static $keyField = null;

        /**
         * @var bool
         */
        protected static $spoted = false;

        /**
         * @var \Cerceau\Model\I\IAuthorizer
         */
        protected $Model;

        protected $loaded = false;

        protected function initialize(){
            if(!static::$name )
                throw new \UnexpectedValueException( 'Store name is not specified in '. get_class( $this ));

            if(!static::$keyField || !array_key_exists( static::$keyField, static::$fieldsOptions ))
                throw new \UnexpectedValueException( 'Store key field is not specified in '. get_class( $this ));

            if(!static::$modelName )
                throw new \UnexpectedValueException( 'Model is not specified in '. get_class( $this ));

            $className = '\\Cerceau\\Model\\'. static::$modelName;
            if(!class_exists( $className, true ) )
                throw new \UnexpectedValueException( 'Model '. $className .' is not implemented' );

            $this->Model = new $className( static::$name );
        }

        /**
         * @param $key
         * @return bool
         */
        public function load( $key ){
            foreach( $this->fields as $offset => $field ){
                /**
                 * @var \Cerceau\Data\Base\Field $field
                 */
                $field->reset( true );
            }
            $key = $this->fields[static::$keyField]->set( $key, false );

            if( static::$spoted )
                $this->Model->setSpotId( $key );

            $a = $this->Model->get( $key );
            if(!$a )
                return false;

            foreach( $this->fields as $offset => $field ){
                if( array_key_exists( $offset, $a )){
                    $value = $this->filter( $offset, $a[$offset] );
                    $field->set( $value, false );
                    if( $value !== $a[$offset] || $this->isFieldInvalid( $offset, $field )){
                        $this->logLoadInvalidData( $key, $offset, $a[$offset] );
                    }
                    $field->resetChanged();
                }
            }
            $this->changed = false;
            $this->loaded = true;
            return true;
        }

        /**
         * @return bool
         */
        public function update(){
            $key = $this->fields[static::$keyField]->toScalar();
            if(!$key )
                return false;

            if( static::$spoted )
                $this->Model->setSpotId( $key );

            $this->validate();
            $data = array();
            foreach( $this->fields as $offset => $field ){
                /**
                 * @var \Cerceau\Data\Base\Field $field
                 */
                if( $offset !== static::$keyField ){
                    $data[$offset] = $field->toScalar();
                    $this->changed = $this->changed || $field->isChanged();
                }
            }
            if(!$this->changed )
                return true;
            $this->changed = !$this->Model->set( $key, $data );
            $this->loaded = !$this->changed;
            return $this->loaded;
        }

        /**
         * @param $key
         * @return bool
         */
        public function remove( $key = null ){
            if(!$key )
                $key = $this->fields[static::$keyField]->toScalar();

            if(!$key )
                return false;

            if( static::$spoted )
                $this->Model->setSpotId( $key );

            return $this->Model->remove( $key );
        }

        /**
         * @return bool
         */
        public function isLoaded(){
            return $this->loaded;
        }

        final protected function logLoadInvalidData( $key, $fieldName, $fieldValue ){
            $message = get_class( $this ) .': row ('. $key .') '
                . $fieldName .' => '. \Cerceau\System\Registry::instance()->Debug()->printVar( $fieldValue );

            \Cerceau\System\Registry::instance()->Logger()->log( 'keystore-invalid-data', $message );
        }
    }
