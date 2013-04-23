<?php
    namespace Cerceau\Database;

	class SpotConnection implements I\ISpotConnection {

        /**
         * @var Connection
         */
        protected static $Instance;

        protected function __construct(){}

        /**
         * @static
         * @return Connection
         */
        public static function instance(){
            if(!static::$Instance ){
                static::$Instance = new static();
            }
            return static::$Instance;
        }

        protected $databases = array();

        /**
         * @param string $name
         * @param int $spotId
         * @return I\IDatabase
         */
        public function get( $name, $spotId ){
            if(!isset( $this->databases[$name][$spotId] )){
                $this->databases[$name][$spotId] = new \Cerceau\Database\Database( \Cerceau\Config\DatabaseSpot::instance( $name, $spotId ), $spotId );
            }
            return $this->databases[$name][$spotId];
        }
	}