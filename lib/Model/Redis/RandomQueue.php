<?php
    namespace Cerceau\Model\Redis;

	class RandomQueue extends Base implements \Cerceau\Model\I\IQueue {

        public function __construct( $name, $spotId = 1 ){
            parent::__construct( $name, $spotId );

            $this->name = $spotId. '_queue_'. $name;
        }

        public function push( $data, $options = array()){
            try {
                $this->client()->sadd( $this->name, $data );
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
                $items = array( $this->name );
                foreach( $args as $item )
                    $items[] = $item['data'];
                call_user_func_array( array( $this->client(), 'sadd' ), $items );
                return true;
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                return false;
            }
        }

        public function pull( $count = 1 ){
            try {
                $load = array();
                while( $count-- ){
                    $val = $this->client()->spop( $this->name );
                    if(!$val )
                        break;
                    $load[] = $val;
                }
                return $load;
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                return false;
            }
        }

        public function len(){
            try {
                return $this->client()->scard( $this->name );
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                return false;
            }
        }
    }
