<?php
    namespace Cerceau\Data\Base;

    use \Cerceau\Data\I as I;

    abstract class Row implements I\IDataRow {
        /**
         * @var array
         */
        protected static $fieldsOptions = array();

        /**
         * @var \Cerceau\Data\I\IStorage
         */
        protected $Storage;

        protected
            $fields = array(),
            $changed = false;

        public function __construct(){
            $this->Storage = new Storage();
            $this->initialize();

            foreach( static::$fieldsOptions as $offset => $options ){
                $className = array_shift( $options );
                $this->fields[$offset] = \Cerceau\Data\Base\Field::create( $className, $this->Storage, $offset, $options );
            }
            $this->postCreate();
        }

        protected function initialize(){}
        protected function postCreate(){}
        protected function beforeValidate(){}

        final public function offsetExists( $offset ){
            return array_key_exists( $offset, $this->fields );
        }

        final public function offsetGet( $offset ){
            $this->Storage[$offset] = $this->fields[$offset]->get();
            return $this->Storage[$offset];
        }

        final public function getScalar( $offset = null ){
            if( is_null( $offset )){
                /**
                 * @var \Cerceau\Data\Base\Field $Field
                 */
                $out = array();
                foreach( $this->fields as $offset => $Field )
                    $out[$offset] = $Field->toScalar();
                return $out;
            }
            if(!array_key_exists( $offset, $this->fields ))
                return null;
            return $this->fields[$offset]->toScalar();
        }

        final public function offsetSet( $offset, $value ){
            if( array_key_exists( $offset, $this->fields )){
                /**
                 * @var \Cerceau\Data\I\IDataField $field
                 */
                $field = $this->fields[$offset];
                $field->set( $this->filter( $offset, $value ));
                $this->changed = $this->changed || $field->isChanged();
            }
        }

        final public function offsetUnset( $offset ){
            if( array_key_exists( $offset, $this->fields ) ){
                /**
                 * @var \Cerceau\Data\Base\Field $field
                 */
                $field = $this->fields[$offset];
                $field->set( null, false );
                $this->changed = true;
            }
        }

        final public function validate(){
            $this->beforeValidate();
            $errors = array();
            foreach( $this->fields as $offset => $Field ){
                $error = $this->isFieldInvalid( $offset, $Field );
                if( false !== $error ){
                    $errors[$offset] = $error;
                }
            }
            if( $errors )
                throw new \Cerceau\Exception\RowValidation( json_encode( $errors ));
        }

        /**
         * @param $offset
         * @param \Cerceau\Data\I\IDataField $Field
         * @return bool|string
         */
        protected function isFieldInvalid( $offset, \Cerceau\Data\I\IDataField $Field ){
            try {
                // general validate
                $Field->validate();
                // custom validate
                $fieldA = explode( '_', $offset );
                $method = 'validate';
                foreach( $fieldA as $fieldB )
                    $method .= ucfirst( $fieldB );

                if( method_exists( $this, $method ))
                    $this->$method();
            }
            catch( \Cerceau\Exception\FieldValidation $E ){
                return $E->getMessage();
            }
            catch( \Cerceau\Exception\RowValidation $E ){
                return json_decode( $E->getMessage());
            }
            return false;
        }

        /**
         * @param array $a
         * @return \Cerceau\Data\I\IStorage
         */
        final public function fetch( array $a ){
            $a = $this->preFetch( $a );
            foreach( $this->fields as $field ){
                /**
                 * @var \Cerceau\Data\Base\Field $field
                 */
                $field->reset();
            }
            foreach( $a as $offset => $value )
                $this->offsetSet( $offset, $value );
            return $this;
        }

        /**
         * @return array
         */
        final public function export(){
            return $this->Storage->export();
        }

        /**
         * @return array
         */
        final public function keys(){
            return array_keys( $this->fields );
        }

        /**
         * @param array $a
         * @return \Cerceau\Data\I\IStorage
         */
        final public function merge( array $a ){
            $a = $this->preFetch( $a );
            foreach( $a as $offset => $value )
                $this->offsetSet( $offset, $value );
            return $this;
        }

        /**
         * Fetches the Storage into Row and back, filtered Row into source Storage
         *
         * @param \Cerceau\Data\I\IStorage $Storage
         * return Row
         */
        final public function set( I\IStorage $Storage ){
            $a = $this->preFetch( $Storage->export() );
            $this->Storage = $Storage;
            foreach( $this->fields as $field ){
                /**
                 * @var \Cerceau\Data\Base\Field $field
                 */
                $field->resetStorage( $Storage );
            }
            foreach( $a as $offset => $value )
                $this->offsetSet( $offset, $value );
        }

        /**
         * @return bool
         */
        public function isChanged(){
            return $this->changed;
        }

        /**
         * @param array $a
         * @return array
         */
        protected function preFetch( $a ){
            return $a;
        }

        /**
         * @param $offset
         * @param $value
         * @return mixed
         */
        protected function filter( $offset, $value ){
            $fieldA = explode( '_', $offset );
            $method = 'filter';
            foreach( $fieldA as $fieldB )
                $method .= ucfirst( $fieldB );

            if( method_exists( $this, $method ) )
                return $this->$method( $value );

            return $value;
        }

        public function fieldsFilter( array $data ){
            $a = array();
            foreach( $this->fields as $offset => $field ){
                if( isset( $data[$offset] ))
                    $a[$offset] = $data[$offset];
            }
            return $a;
        }

    }
