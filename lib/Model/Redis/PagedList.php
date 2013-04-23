<?php
    namespace Cerceau\Model\Redis;

	class PagedList extends Base implements \Cerceau\Model\I\IList {
        const PAGE_SIZE = 100;

        protected $counter = null;

        public function __construct( $name, $spotId = 1 ){
            parent::__construct( $name, $spotId );

            $this->name = $spotId. '_plist_'. $name .'_';
        }

        public function append( $key, array $data ){
            try {
                $this->counter = intval( $this->client()->incr( $this->name . $key .'_counter' ));
                $page = intval(( $this->counter - 1 ) / self::PAGE_SIZE );

                if(!$this->client()->rpush( $this->name . $key .'_'. $page , serialize( $data )))
                    return false;

                return $this->counter;
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
                \Cerceau\System\Registry::instance()->Logger()->log( 'redis-errors', 'Couldn\'t set data for '. $this->name . $key );
            }
            return false;
        }

        public function get( $key, $idx ){
            try {
                $idx--;
                $page = intval( $idx / self::PAGE_SIZE );
                $idx  = $idx % self::PAGE_SIZE;

                $val = $this->client()->lindex( $this->name . $key .'_'. $page, $idx );
                if( $val )
                    return unserialize( $val );
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
            }
            return false;
        }

        public function update( $key, $idx, array $data ){
            try {
                $idx--;
                $page = intval( $idx / self::PAGE_SIZE );
                $idx  = $idx % self::PAGE_SIZE;

                if( $this->client()->lset( $this->name . $key .'_'. $page, $idx, serialize( $data )))
                    return true;
            }
            catch( \Exception $E ){
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
            }
            return false;
        }

        public function range( $key, $fromIdx, $count = null ){
            try {
                if(!$count )
                    $count = self::PAGE_SIZE;

                $fromIdx--;
                $page = intval( $fromIdx / self::PAGE_SIZE );
                $fromIdx  = $fromIdx % self::PAGE_SIZE;
                $toIdx = $fromIdx + $count - 1;

                $list = $this->client()->lrange( $this->name . $key .'_'. $page, $fromIdx, $toIdx );
                if(!$list )
                    return array();

                if( $toIdx > 100 ){
                    $toIdx %= self::PAGE_SIZE;
                    $list2 = $this->client()->lrange( $this->name . $key .'_'. ( $page + 1 ), 0, $toIdx );
                    if(!$list2 )
                        $list2 = array();

                    $list = array_merge( $list, $list2 );
                }

                return $list;
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
                if( $this->counter === null ){
                    $this->counter = intval( $this->client()->get( $this->name . $key .'_counter' ));
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
