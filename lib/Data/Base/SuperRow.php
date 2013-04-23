<?php
    namespace Cerceau\Data\Base;

    use \Cerceau\Data\I as I;

	abstract class SuperRow implements I\IDataRow {
        /**
         * @var array
         */
        protected static $subrowsOptions = array();

        /**
         * @var \Cerceau\Data\I\IStorage
         */
        protected $Storage;

        protected $subrows = array();

        final public function __construct(){
            $this->Storage = new Storage();
            $this->initialize();

            foreach( static::$subrowsOptions as $className => $name ){
                $className = '\\Cerceau\\Data\\'. $className;
                /**
                 * @var \Cerceau\Data\I\IDataRow $Row
                 */
                $this->subrows[$name] = $Row = new $className();
                if(!$Row instanceof I\IDataRow )
                    throw new \UnexpectedValueException( 'Subrow "'. $name .'" must be an instance of IDataRow' );
                $Row->set( $this->Storage );
            }
            $this->postCreate();
        }

        protected function initialize(){}
        protected function postCreate(){}

        final public function offsetExists( $offset ){
            return $this->Storage->offsetExists( $offset );
        }

        final public function offsetGet( $offset ){
            return $this->Storage->offsetGet( $offset );
        }

        final public function offsetSet( $offset, $value ){
            if( $this->Storage->offsetExists( $offset )){
                foreach( $this->subrows as $Row )
                    $Row[$offset] = $value;
            }
        }

        final public function offsetUnset( $offset ){
            if( $this->Storage->offsetExists( $offset )){
                foreach( $this->subrows as $Row )
                    unset( $Row[$offset] );
            }
        }

        final public function validate(){
            $errors = array();
            foreach( $this->subrows as $Row ){
                $rowErrors = $this->isRowInvalid( $Row );
                if( false !== $rowErrors ){
                    $errors += $rowErrors;
                }
            }
            if( $errors )
                throw new \Cerceau\Exception\RowValidation( json_encode( $errors ));
        }

        /**
         * @param \Cerceau\Data\I\IDataRow $Row
         * @return bool|array
         */
        protected function isRowInvalid( \Cerceau\Data\I\IDataRow $Row ){
            try {
                $Row->validate();
            }
            catch( \Cerceau\Exception\RowValidation $E ){
                return json_decode( $E->getMessage());
            }
            return false;
        }

        final public function fetch(array $a){
            $a = $this->preFetch( $a );
            foreach( $this->subrows as $Row ){
                /**
                 * @var \Cerceau\Data\I\IDataRow $Row
                 */
                $Row->fetch( $a );
            }
            return $this;
        }

        /**
         * @param array $a
         * @return array
         */
        protected function preFetch( $a ){
            return $a;
        }

        final public function export(){
            return $this->Storage->export();
        }

        final public function keys(){
            return $this->Storage->keys();
        }

        /**
         * @param array $a
         * @return \Cerceau\Data\I\IStorage
         */
        final public function merge( array $a ){
            foreach( $this->subrows as $Row ){
                /**
                 * @var \Cerceau\Data\I\IDataRow $Row
                 */
                $Row->merge( $a );
            }
            return $this;
        }

        /**
         * Fetches the Storage into Row and back, filtered Row into source Storage
         *
         * @param \Cerceau\Data\I\IStorage $Storage
         * return Row
         */
        final public function set( I\IStorage $Storage ){
            $this->Storage = $Storage;
            foreach( $this->subrows as $Row ){
                /**
                 * @var \Cerceau\Data\I\IDataRow $Row
                 */
                $Row->set( $Storage );
            }
        }

        /**
         * @return bool
         */
        public function isChanged(){
            foreach( $this->subrows as $Row ){
                /**
                 * @var \Cerceau\Data\I\IDataRow $Row
                 */
                if( $Row->isChanged())
                    return true;
            }
            return false;
        }

        public function fieldsFilter( array $data ){
            $a = array();
            foreach( $this->subrows as $Row ){
                /**
                 * @var \Cerceau\Data\I\IDataRow $Row
                 */
                $a += $Row->fieldsFilter( $a );
            }
            return $a;
        }
    }
