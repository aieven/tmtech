<?php
    namespace Cerceau\Model\Redis;

	class Lock extends Base {

        public function __construct( $name, $spotId = 1 ){
            parent::__construct( $name, $spotId );

            $this->name = $spotId. '_lock_'. $name;
        }

        public function set( $value = 1 ){
            try {
                return $this->client()->setnx( $this->name, $value );
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                return false;
            }
        }

        public function get(){
            try {
                return $this->client()->get( $this->name );
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                return false;
            }
        }
    }
