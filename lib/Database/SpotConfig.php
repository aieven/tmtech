<?php
    namespace Cerceau\Database;

	class SpotConfig extends Config {
        const
            HOST_PARAM_NAME = 'host',
            POSTFIX_PARAM_NAME = 'dbname',

            DATABASES_CONFIG_NAME = 'databases',
            SPOTS_CONFIG_NAME = 'databases_spots'
        ;

        protected static $spotsDatabases = array();
        protected static $spots = array();

        /**
         * @param $databaseId
         */
        private function setSpotDatabase( $databaseId ){
            $this->config[self::HOST_PARAM_NAME] = static::$spotsDatabases[$databaseId];
            $this->config[self::POSTFIX_PARAM_NAME] .= '_'. $databaseId;
        }

        /**
         * @static
         * @param string $name
         * @param int $spotId
         * @return Config
         * @throws \UnexpectedValueException
         */
        public static function instance( $name, $spotId = null ){
            if(!array_key_exists( $name, static::$databases ))
                throw new \UnexpectedValueException( 'Database "'. $name .'" is not defined' );

            if(!static::$spots ){
                $DynamicConfigFile = \Cerceau\Utilities\DynamicConfigFile::instance();
                static::$spots = $DynamicConfigFile->read( self::SPOTS_CONFIG_NAME );
                static::$spotsDatabases = $DynamicConfigFile->read( self::DATABASES_CONFIG_NAME );
            }

            if(!array_key_exists( $spotId, static::$spots ))
                throw new \UnexpectedValueException( 'Spot #'. $spotId .' is not defined' );

            $databaseId = static::$spots[$spotId];
            if(!array_key_exists( $databaseId, static::$spotsDatabases ))
                throw new \UnexpectedValueException( 'Spot database #'. $databaseId .' is not defined' );

            if(!array_key_exists( $name.$databaseId, self::$configs )){
                self::$configs[$name.$databaseId] = new static( static::$databases[$name], TablesConfig\Base::instance( $name ));
                self::$configs[$name.$databaseId]->setSpotDatabase( $databaseId );
            }

            return self::$configs[$name.$databaseId];
        }
	}