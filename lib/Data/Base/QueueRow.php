<?php
    namespace Cerceau\Data\Base;

	abstract class QueueRow extends Row {
        /**
         * @var string
         */
        protected static $modelName = 'Redis\\Queue';

        /**
         * @var string
         */
        protected static $serializerName = 'Common';

        /**
         * @var array
         */
        protected static $serializerOptions = array();

        /**
         * @var string
         */
        protected static $queueName = null;

        /**
         * @var string
         */
        protected static $db = null;

        /**
         * @var array
         */
        protected $pullStack = null;

        /**
         * @var array
         */
        protected $pushStack = array();

        /**
         * @var \Cerceau\Model\I\IQueue
         */
        protected $Model;

        /**
         * @var \Cerceau\Data\I\ISerializer
         */
        protected $Serializer;

        protected function initialize(){
            if(!static::$queueName )
                throw new \UnexpectedValueException( 'Mail name is not specified in '. get_class( $this ));

            $className = '\\Cerceau\\Model\\'. static::$modelName;
            if(!class_exists( $className, true ) )
                throw new \UnexpectedValueException( 'Model '. $className .' is not implemented' );

            $serializerName = '\\Cerceau\\Data\\Base\\Serializer\\'. static::$serializerName;
            if(!class_exists( $serializerName, true ) )
                throw new \UnexpectedValueException( 'Serializer '. $serializerName .' is not implemented' );

            $this->Model = new $className( static::$queueName );
            $this->Serializer = new $serializerName();
            $this->Serializer->options( static::$serializerOptions );
        }

        /**
         * @param bool $stacking
         * @return bool
         */
        public function push( $stacking = false ){
            $this->validate();
            $data = array();
            foreach( $this->fields as $offset => $field ){
                /**
                 * @var \Cerceau\Data\Base\Field $field
                 */
                $value = $field->toScalar();
                $data[$offset] = $value;
            }
            if( $stacking ){
                $this->pushStack[] = array(
                    'data' => $this->Serializer->serialize( $data ),
                    'options' => $this->getOptions(),
                );
                return true;
            }else
                return $this->Model->push( $this->Serializer->serialize( $data ), $this->getOptions());
        }

        public function pushStack(){
            if( empty( $this->pushStack ))
                return true;

            $result = call_user_func_array( array( $this->Model, 'pushStack' ), $this->pushStack );
            $this->pushStack = array();
            return $result;
        }

        /**
         * @param int $count
         * @return bool
         */
        public function pull( $count = 1 ){
            if( empty( $this->pullStack )){
                $this->pullStack = $this->Model->pull( $count );
                if( empty( $this->pullStack ))
                    return false;
            }
            $a = $this->Serializer->unserialize( array_shift( $this->pullStack ));
            $a = $this->preLoad( $a );
            foreach( $this->fields as $offset => $field ){
                /**
                 * @var \Cerceau\Data\Base\Field $field
                 */
                if( array_key_exists( $offset, $a )){
                    $value = $this->filter( $offset, $a[$offset] );
                    $field->set( $value, false );
                    $field->resetChanged();
                }
                else
                    $field->reset( true );
            }
            return true;
        }

        /**
         * @return int
         */
        public function len(){
            return $this->Model->len();
        }

        /**
         * @param array $a
         * @return array
         */
        protected function preLoad( $a ){
            return $a;
        }

        /**
         * @return array
         */
        protected function getOptions(){
            return array();
        }
    }
