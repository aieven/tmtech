<?php
    namespace Cerceau\Model\Redis;

	class Set extends Base implements \Cerceau\Model\I\ISet {

        protected $counter = null;

        public function __construct( $name, $spotId = 1 ){
            parent::__construct( $name, $spotId );

            $this->name = $spotId. '_set_'. $name .'_';
        }

        public function append( $key, $member ){
            try {
                $args = func_get_args();
                $args[0] = $this->name . $key;
                if( call_user_func_array( array( $this->client(), 'sadd' ), $args )){
                    if( $this->counter !== null )
                        $this->counter += count( $args ) - 1;
                    return true;
                }
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                \Cerceau\System\Registry::instance()->Logger()->log( 'redis-errors', 'Couldn\'t append data to '. $this->name . $key .' ['. $member .']' );
            }
            return false;
        }

        /**
         * @param $key
         * @param $member
         * @return bool
         */
        public function exists( $key, $member ){
            try {
                if( $this->client()->sismember( $this->name . $key, $member ))
                    return true;
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                \Cerceau\System\Registry::instance()->Logger()->log( 'redis-errors', 'Couldn\'t find data of '. $this->name . $key .' ['. $member .']' );
            }
            return false;
        }

        /**
         * @param $key
         * @param $member
         * @return bool
         */
        public function remove( $key, $member ){
            try {
                $args = func_get_args();
                $args[0] = $this->name . $key;
                if( call_user_func_array( array( $this->client(), 'srem' ), $args )){
                    if( $this->counter !== null )
                        $this->counter -= count( $args ) - 1;
                    return true;
                }
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                \Cerceau\System\Registry::instance()->Logger()->log( 'redis-errors', 'Couldn\'t remove data from '. $this->name . $key .' ['. $member .']' );
            }
            return false;
        }

        /**
         * @param $key
         * @return bool
         */
        public function clear( $key ){
            try {
                $this->client()->del( $this->name . $key );
                $this->counter = 0;
                return true;
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                \Cerceau\System\Registry::instance()->Logger()->log( 'redis-errors', 'Couldn\'t clear data from '. $this->name . $key );
            }
            return false;
        }

        /**
         * @param $key
         * @return array
         */
        public function members( $key ){
            try {
                $members = $this->client()->smembers( $this->name . $key );
                if( $members ){
                    $this->counter = count( $members );
                    return $members;
                }
                return array();
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                \Cerceau\System\Registry::instance()->Logger()->log( 'redis-errors', 'Couldn\'t get data from '. $this->name . $key );
                return false;
            }
        }


        /**
         * @param $key
         * @return int
         */
        public function count( $key ){
            try {
                if( $this->counter === null ){
                    $this->counter = intval( $this->client()->scard( $this->name . $key ));
                    if(!$this->counter )
                        $this->counter = 0;
                }
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                return false;
            }
            return $this->counter;
        }
    }
