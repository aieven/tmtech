<?php
    namespace Cerceau\Model\Redis;

	class AccumulativeQueue extends Base implements \Cerceau\Model\I\IQueue {

        public function __construct( $name, $spotId = 1 ){
            parent::__construct( $name, $spotId );

            $this->name = $spotId. '_queue_'. $name;
        }

        public function push( $data, $options = array()){
            try {
                $this->client()->zincrby( $this->name, 1, $data );
                return true;
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                return false;
            }
        }

        public function pushStack( $item ){
            try {
                $args = func_get_args();
                foreach( $args as $item )
                    $this->client()->zincrby( $this->name, 1, $item['data'] );
                return true;
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                return false;
            }
        }

        public function pull( $count = 1 ){
            try {
                $range = $this->client()->zrevrangebyscore( $this->name, '+inf', 1, array( 'limit' => array( 0, $count ))) ?: array();
                if( $range ){
                    $args = $range;
                    array_unshift( $args, $this->name );
                    call_user_func_array( array( $this->client(), 'zrem' ), $args );
                }
                return $range;
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                return false;
            }
        }

        public function len(){
            try {
                return $this->client()->zcount( $this->name, '-inf', '+inf' );
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                return false;
            }
        }
    }
