<?php
    namespace Cerceau\Model\Memcached;

	abstract class Base {
        protected
            $spotId = 1,
            $name;

        public function __construct( $name, $spotId = 1 ){
            if(!$name )
                throw new \LogicException( 'Name is not set in '. __CLASS__ );
            if( $spotId )
                $this->spotId = $spotId;
        }

        /**
         * @param int $id
         */
        final public function setSpotId( $id ){
            $this->spotId = intval(( intval( $id ) - 1 ) / \Cerceau\Config\Constants::REDIS_SPOT_SIZE ) + 1;
        }

        /**
         * @return \Memcached
         */
        final protected function client(){
            return \Cerceau\NoSQL\Memcached::instance()->get( $this->spotId );
        }
    }