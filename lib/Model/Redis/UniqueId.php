<?php
    namespace Cerceau\Model\Redis;

	class UniqueId extends Base implements \Cerceau\Model\I\IUniqueId {
        /**
         * @var callback $callback
         */
        protected $callback = null;

        /**
         * @var array
         */
        protected $allocated = array();

        public function __construct( $name, $spotId = 1 ){
            parent::__construct( $name, $spotId );

            $this->name = $spotId. '_uuid_'. $name .'_';
        }

        /**
         * @param $callback
         * @throws \UnexpectedValueException
         */
        public function setMethod( $callback ){
            if(!is_callable( $callback ))
                throw new \UnexpectedValueException( 'Method is not callable in '.__CLASS__.'::'.__METHOD__ );
            $this->callback = $callback;
        }

        protected function defaultMethod( $id ){
            return sha1( $id .':'. mt_rand( 13, time()));
        }

        public function allocate( $id = 0 ){
            try {
                $tries = 10;
                while( $tries-- ){
                    $key = $this->callback ? call_user_func( $this->callback, $id ) : $this->defaultMethod( $id );
                    if( $this->client()->setnx( $this->name . $key, $id ))
                        break;
                }
                if(!$tries )
                    throw new \RuntimeException( 'Couldn\'t allocate an unique id for '. $this->name );

                if(!$id )
                    $this->allocated[$key] = true;
                return $key;
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                return false;
            }
        }

        public function exists( $key ){
            try {
                return $this->client()->exists( $this->name . $key );
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                throw $E;
            }
        }

        public function get( $key ){
            try {
                return intval( $this->client()->get( $this->name . $key ));
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                return false;
            }
        }

        public function set( $key, $id ){
            if(!isset( $this->allocated[$key] ))
                throw new \UnexpectedValueException( 'You have to allocate key before set' );

            try {
                $this->client()->set( $this->name . $key, $id );
                unset( $this->allocated[$key] );
                return true;
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                return false;
            }
        }
    }
