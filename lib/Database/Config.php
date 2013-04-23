<?php
    namespace Cerceau\Database;

	class Config implements I\IConfig {
        protected static $databases = array();
        protected static $configs = array();

        /**
         * @var I\ITablesConfig
         */
        protected $TablesConfig;

        /**
         * @var I\IDriver
         */
        private $Driver;

        /**
         * @var I\ISQLTemplator
         */
        private $Templator;

        /**
         * @var array
         */
        protected $config;

        protected function __construct( array $config, I\ITablesConfig $TablesConfig ){
            $this->config = $config;
            $this->TablesConfig = $TablesConfig;
        }

        public function get( $name ){
            return isset( $this->config[$name] ) ? $this->config[$name] : '';
        }

        public function getDriver(){
            if(!$this->Driver ){
                $driverClass = '\\Cerceau\\Database\\Driver\\'. $this->get( 'driver' );
                $this->Driver = new $driverClass( $this );
            }
            return $this->Driver;
        }

        public function getTemplator(){
            if(!$this->Templator ){
                $this->Templator = new \Cerceau\Database\Helper\BlitzTemplator( $this );
            }
            return $this->Templator;
        }

        public function getTablesConfig(){
            return $this->TablesConfig;
        }

        /**
         * @static
         * @param $name
         * @return Config
         * @throws \UnexpectedValueException
         */
        public static function instance( $name ){
            if(!array_key_exists( $name, static::$databases ))
                throw new \UnexpectedValueException( 'Database "'. $name .'" is not defined' );

            if(!array_key_exists( $name, self::$configs ))
                self::$configs[$name] = new static( static::$databases[$name], TablesConfig\Base::instance( $name ));

            return self::$configs[$name];
        }
	}