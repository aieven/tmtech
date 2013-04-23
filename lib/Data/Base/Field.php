<?php
    namespace Cerceau\Data\Base;

    use \Cerceau\Data\I as I;

	abstract class Field implements I\IDataField {
        /**
         * @var \Cerceau\Data\I\IStorage
         */
        protected $Storage;

        protected
            $offset,
            $default = null,
            $load = false,
            $autoIncrement = false,
            $foreign = false,
            $validationPrefix = '',
            $changed = false,
            $validators = array();

        /**
         * create only by factory
         *
         * @param \Cerceau\Data\I\IStorage $Storage
         * @param $offset
         */
        private function __construct( I\IStorage $Storage, $offset ){
            $Storage[$offset] = null;
            $this->Storage = $Storage;
            $this->offset = $offset;
        }

        /**
         * @param \Cerceau\Data\I\IStorage $Storage
         */
        public function resetStorage( I\IStorage $Storage ){
            $this->Storage = $Storage;
            $this->reset();
        }

        /**
         * @param string|int $option
         * @param mixed $value
         * @return bool
         */
        final private function setOption( &$option, $value ){
            $noValue = false;
            if( is_numeric( $option ) ){
                $option = $value;
                $value = null;
                $noValue = true;
            }

            $method = 'set'. ucfirst( $option );
            if(!method_exists( $this, $method ))
                return false;

            if( $noValue )
                $this->$method();
            else
                $this->$method( $value );

            return true;
        }

        private function setDefault( $value ){
            $this->default = $value;
            $this->set( $value, false );
        }

        private function setLoad(){
            $this->load = true;
        }

        private function setAutoIncrement(){
            $this->autoIncrement = true;
        }

        private function setForeign(){
            $this->foreign = true;
        }

        private function setValidationPrefix( $validationPrefix ){
            $this->validationPrefix = $validationPrefix .'.';
        }

        final public function isLoad(){
            return $this->load;
        }

        final public function isAutoIncrement(){
            return $this->autoIncrement;
        }

        final public function isForeign(){
            return $this->foreign;
        }

        /**
         * @param array $validators
         * @throws \UnexpectedValueException
         */
        private function setValidation( array $validators ){
            if(!is_array( $validators ))
                throw new \UnexpectedValueException( get_class( $this ) .' validation option is not valid' );

            $this->validators += $validators;
        }

        /**
         * @return bool
         * @throws \Cerceau\Exception\FieldValidation
         * @throws \UnexpectedValueException
         */
        final public function validate(){
            foreach( $this->validators as $validator => $value ){
                $noValue = false;
                if( is_numeric( $validator ) ){
                    $validator = $value;
                    $value = null;
                    $noValue = true;
                }

                $method = 'validate'. ucfirst( $validator );
                if(!method_exists( $this, $method ) )
                    throw new \UnexpectedValueException( 'Validator "'. $validator .'" for '. get_class( $this ) .' is not implemented' );

                if( $noValue )
                    $result = $this->$method();
                else
                    $result = $this->$method( $value );

                if(!$result )
                    throw new \Cerceau\Exception\FieldValidation( $this->validationPrefix . $validator );
            }
            return true;
        }

        /**
         * @return mixed
         */
        public function get(){
            return $this->Storage[$this->offset];
        }

        /**
         * Standard function
         *
         * @return string|int
         */
        public function toScalar(){
            $value = $this->get();
            if( is_null( $value ) )
                return null;
            if( is_scalar( $value ) )
                return $value;
            return serialize( $value );
        }

        /**
         * @param $value
         * @param bool $fetch
         * @return mixed
         */
        public function set( $value, $fetch = true ){
            return $this->setValue( $value );
        }

        /**
         * Inner setter
         *
         * @param $value
         * @return mixed
         */
        final protected function setValue( $value ){
            $this->changed = $value !== $this->Storage[$this->offset];
            return $this->Storage[$this->offset] = $value;
        }

        public function isChanged(){
            return $this->changed;
        }

        public function resetChanged(){
            $this->changed = false;
        }

        /**
         * @param bool $default
         * @return mixed
         */
        public function reset( $default = true ){
            $this->resetChanged();
            if( $default )
                return $this->set( $this->default );
            return $this->set( null, false );
        }

        /**
         * @static
         * @param $class
         * @param \Cerceau\Data\I\IStorage $Storage
         * @param $offset
         * @param array|null $options
         * @return \Cerceau\Data\I\IDataField
         * @throws \UnexpectedValueException
         */
        final public static function create( $class, I\IStorage $Storage, $offset, array $options = null ){
            $className = __CLASS__.'\\Field'. $class;
            if(!class_exists( $className, true ) )
                throw new \UnexpectedValueException( 'Class '. $className .' not implemented' );

            /**
             * @var \Cerceau\Data\I\IDataField $field
             */
            $field = new $className( $Storage, $offset );
            if(!( $field instanceof I\IDataField ))
                throw new \UnexpectedValueException( 'Class '. $className .' must implement interface \\Cerceau\\Data\\I\\IDataField' );

            if( $field instanceof Field ){
                /**
                 * @var Field $field
                 */
                foreach( $options as $option => $value ){
                    if(!$field->setOption( $option, $value ) )
                        throw new \UnexpectedValueException( 'Option setter for '. $class .' "'. $option .'" is not implemented' );
                }
            }
            return $field;
        }
    }
