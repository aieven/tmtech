<?php
    namespace Cerceau\NoSQL;

	class Memcached {

        /**
         * @var Redis
         */
        protected static $Instance;

        protected function __construct(){}

        /**
         * @static
         * @return Redis
         */
        public static function instance(){
            if(!static::$Instance ){
                static::$Instance = new static();
            }
            return static::$Instance;
        }

        /**
         * @var \Memcached
         */
        protected $MCache;

        /**
         * @param int $spotId
         * @return \Memcached
         */
        public function get( $spotId = 1 ){
            if(!isset( $this->MCache )){
                $this->MCache = new \Memcached();
                $this->MCache->addServer( 'localhost', 11211 );
            }
            return $this->MCache;
        }
	}