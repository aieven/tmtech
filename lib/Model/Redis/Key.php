<?php
    namespace Cerceau\Model\Redis;

	class Key extends Base {

        public function __construct( $name, $spotId = 1 ){
            parent::__construct( $name, $spotId );

            $this->name = $spotId .'_k_'. $name .'_';
        }

        public function set( $key, $value ){
            try {
                $this->client()->set( $this->name . $key, $value );
                return true;
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                return false;
            }
        }

        public function get( $key ){
            try {
                $val = $this->client()->get( $this->name . $key );
                if( $val )
                    return $val;
                else
                    return false;
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                return false;
            }
        }

        public function remove( $key ){
            try {
                $this->client()->del( $this->name . $key );
                return true;
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                return false;
            }
        }
    }
