<?php
    namespace Cerceau\Data\Base;

    abstract class DbRow extends Row {
        /**
         * @var string
         */
        protected static $modelName = 'Base';

        /**
         * @var string
         */
        protected static $db = null;

        /**
         * @var string
         */
        protected static $table = null;

        /**
         * @var \Cerceau\Model\I\IModel
         */
        protected $Model;

        protected $loaded = false;

        protected function initialize(){
            if(!static::$modelName )
                throw new \UnexpectedValueException( 'Model is not specified in '. get_class( $this ) );

            $className = '\\Cerceau\\Model\\'. static::$modelName;
            if(!class_exists( $className, true ) )
                throw new \UnexpectedValueException( 'Model '. $className .' is not implemented' );

            $this->Model = new $className( static::$db, static::$table );
        }

        /**
         * @param $params
         * @return bool
         */
        public function load( $params ){
            $params = $this->preFetch( $params );
            $data = array();
            foreach( $this->fields as $offset => $field ){
                /**
                 * @var \Cerceau\Data\Base\Field $field
                 */
                if( $field->isLoad() && array_key_exists( $offset, $params )){
                    $field->set( $this->filter( $offset, $params[$offset] ), false );
                    $data[$offset] = $field->toScalar();
                }
            }
            if(!$data )
                return false;
            if(!$this->Model->load( $data ) )
                return false;

            $a = $this->Model->result();
            foreach( $this->fields as $offset => $field ){
                if( array_key_exists( $offset, $a )){
                    $value = $this->filter( $offset, $a[$offset] );
                    $field->set( $value, false );
                    if( $value !== $a[$offset] || $this->isFieldInvalid( $offset, $field )){
                        $this->logLoadInvalidData( $data, $offset, $a[$offset] );
                    }
                    $field->resetChanged();
                }
                else
                    $field->reset( true );
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
            $data = array();
            $autoIncrement = null;
            foreach( $this->fields as $offset => $field ){
                /**
                 * @var \Cerceau\Data\Base\Field $field
                 */
                if( $field->isForeign())
                    continue;
                if( is_null( $autoIncrement ) && $field->isAutoIncrement())
                    $autoIncrement = $offset;
                else
                    $data[$offset] = $field->toScalar();
                $field->resetChanged();
            }
            $this->loaded = $this->Model->create( $data );
            if( $autoIncrement ){
                /**
                 * @var \Cerceau\Data\Base\Field $field
                 */
                $field = $this->fields[$autoIncrement];
                $field->set( $this->Model->result());
                $field->resetChanged();
            }
            $this->changed = !$this->loaded;
            return $this->loaded;
        }

        /**
         * @param bool $validate
         * @return bool
         */
        public function update( $validate = true ){
            if(!$this->loaded )
                return false;
            if( $validate )
                $this->validate();
            $data = array();
            $by = array();
            foreach( $this->fields as $offset => $field ){
                /**
                 * @var \Cerceau\Data\Base\Field $field
                 */
                if( $field->isForeign())
                    continue;
                if( $field->isLoad()){
                    $by[$offset]   = $field->toScalar();
                }else{
                    $data[$offset] = $field->toScalar();
                    $this->changed = $this->changed || $field->isChanged();
                }
            }
            if(!$this->changed )
                return true;
            $this->changed = !$this->Model->update( $data, $by );
            return !$this->changed;
        }

        /**
         * @return bool
         */
        public function isLoaded(){
            return $this->loaded;
        }

        final protected function logLoadInvalidData( $data, $fieldName, $fieldValue ){
            $loadFields = array();
            foreach( $data as $k => $v ){
                $loadFields[] = $k .' => '. $v;
            }
            $message = get_class( $this ) .': row ( '. implode( ', ', $loadFields ) .' ) '
                . $fieldName .' => '. \Cerceau\System\Registry::instance()->Debug()->printVar( $fieldValue );

            \Cerceau\System\Registry::instance()->Logger()->log( 'db-invalid-data', $message );
        }
    }
