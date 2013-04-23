<?php
    namespace Cerceau\Model\Redis;

	class ScoredList extends Base implements \Cerceau\Model\I\IList {

        protected $descending = true;

        public function __construct( $name, $spotId = 1 ){
            parent::__construct( $name, $spotId );

            $this->name = $spotId. '_sl_'. $name .'_';
        }

        public function setDescending( $desc = true ){
            $this->descending = $desc;
        }

        public function append( $key, array $data ){
            return false;
        }

        public function get( $key, $idx ){
            try {
                $method = $this->descending ? 'zrevrangebyscore' : 'zrangebyscore';
                $args = array( $this->name . $key, $idx, $this->descending ? '-inf' : '+inf', array( 'limit' => array( 0, 1 )));
                $val = call_user_func_array( array( $this->client(), $method ), $args );
                if( $val )
                    return unserialize( reset( $val ));
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
            }
            return false;
        }

        public function update( $key, $idx, array $data ){
            try {
                if( $this->client()->zadd( $this->name . $key, $idx, serialize( $data )))
                    return true;
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
            }
            return false;
        }

        public function range( $key, $fromIdx, $count = null ){
            try {
                $method = $this->descending ? 'zrevrangebyscore' : 'zrangebyscore';
                if( $this->descending )
                    $args = array( $this->name . $key, $fromIdx ? '('. $fromIdx : '+inf', '-inf' );
                else
                    $args = array( $this->name . $key, $fromIdx ? '('. $fromIdx : '-inf', '+inf' );
                if( $count )
                    $args[] = array( 'limit' => array( 0, $count ));

                return call_user_func_array( array( $this->client(), $method ), $args ) ?: array();
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                return false;
            }
        }

        public function remove( $key, $start, $stop = null ){
            try {
                if(!$stop )
                    $stop = $this->descending ? 0 : -1;

                if( $this->descending )
                    list( $start, $stop ) = array( -1 - $stop, -1 - $start );

                return $this->client()->zremrangebyrank( $this->name . $key, $start, $stop );
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                return false;
            }
        }

        /**
         * @param $key
         * @return int
         */
        public function count( $key ){
            try {
                return intval( $this->client()->zcard( $this->name . $key ));
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                return false;
            }
        }
    }
