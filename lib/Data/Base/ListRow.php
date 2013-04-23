<?php
    namespace Cerceau\Data\Base;

	abstract class ListRow extends Row {
        /**
         * @var string
         */
        protected static $modelName = 'Redis\\PagedList';

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
        protected static $idxField = null;

        /**
         * @var bool
         */
        protected static $spoted = false;

        /**
         * @var \Cerceau\Model\I\IList
         */
        protected $Model;

        protected $loaded = false;

        protected function initialize(){
            if(!static::$name )
                throw new \UnexpectedValueException( 'Store name is not specified in '. get_class( $this ));

            if(!static::$keyField || !array_key_exists( static::$keyField, static::$fieldsOptions ))
                throw new \UnexpectedValueException( 'Store key field is not specified in '. get_class( $this ));

            if(!static::$idxField || !array_key_exists( static::$idxField, static::$fieldsOptions ))
                throw new \UnexpectedValueException( 'Store index field is not specified in '. get_class( $this ));

            if(!static::$modelName )
                throw new \UnexpectedValueException( 'Model is not specified in '. get_class( $this ));

            $className = '\\Cerceau\\Model\\'. static::$modelName;
            if(!class_exists( $className, true ) )
                throw new \UnexpectedValueException( 'Model '. $className .' is not implemented' );

            $this->Model = new $className( static::$name );
        }

        /**
         * @param $key
         * @param $idx
         * @return bool
         */
        public function load( $key, $idx ){
            foreach( $this->fields as $field ){
                /**
                 * @var \Cerceau\Data\Base\Field $field
                 */
                $field->reset( true );
            }

            $key = $this->fields[static::$keyField]->set( $key, false );
            $idx = $this->fields[static::$idxField]->set( $idx, false );

            if( static::$spoted )
                $this->Model->setSpotId( $key );

            $a   = $this->Model->get( $key, $idx );
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
         * @param bool $validate
         * @return bool
         */
        public function create( $validate = true ){
            if( $validate )
                $this->validate();

            $key = $this->fields[static::$keyField]->toScalar();
            $data = array();
            $autoIncrement = null;
            foreach( $this->fields as $offset => $field ){
                /**
                 * @var \Cerceau\Data\Base\Field $field
                 */
                if( $field->isForeign())
                    continue;
                if( $offset !== static::$keyField && $offset !== static::$idxField ){
                    $data[$offset] = $field->toScalar();
                    $field->resetChanged();
                }
            }
            if( static::$spoted )
                $this->Model->setSpotId( $key );

            $idx = $this->Model->append( $key, $data );
            if( false === $idx )
                return false;

            $this->fields[static::$idxField]->set( $idx, false );
            $this->changed = false;
            $this->loaded = true;
            return true;
        }

        /**
         * @return bool
         */
        public function update(){
            $key = $this->fields[static::$keyField]->toScalar();
            $idx = $this->fields[static::$idxField]->toScalar();
            if(!$key || false === $idx )
                return false;

            $this->validate();
            $data = array();
            foreach( $this->fields as $offset => $field ){
                /**
                 * @var \Cerceau\Data\Base\Field $field
                 */
                if( $offset !== static::$keyField && $offset !== static::$idxField ){
                    $data[$offset] = $field->toScalar();
                    $this->changed = $this->changed || $field->isChanged();
                }
            }
            if(!$this->changed )
                return true;

            if( static::$spoted )
                $this->Model->setSpotId( $key );

            $this->changed = !$this->Model->update( $key, $idx, $data );
            return !$this->changed;
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

            \Cerceau\System\Registry::instance()->Logger()->log( 'keystorelist-invalid-data', $message );
        }
    }
