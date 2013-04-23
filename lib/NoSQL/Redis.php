<?php
    namespace Cerceau\NoSQL;

	class Redis {

        const
            PORTS_CONFIG_NAME = 'redises',
            SPOTS_CONFIG_NAME = 'redises_spots'
        ;

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

        protected static $redises = array();
        protected static $ports = array();
        protected static $spots = array();

        protected $instances = array();

        /**
         * @param int $spotId
         * @return \Predis\Client
         * @throws \UnexpectedValueException
         */
        public function get( $spotId = 1 ){
            if(!isset( $this->instances[$spotId] )){
                if(!static::$spots ){
                    $DynamicConfigFile = \Cerceau\Utilities\DynamicConfigFile::instance();
                    static::$ports = $DynamicConfigFile->read( static::PORTS_CONFIG_NAME );
                    static::$spots = $DynamicConfigFile->read( static::SPOTS_CONFIG_NAME );
                }

                if(!isset( static::$spots[$spotId] ))
                    throw new \UnexpectedValueException( 'Spot #'. $spotId .' is not defined' );

                $redisId = static::$spots[$spotId];
                if(!isset( static::$ports[$redisId] ))
                    throw new \UnexpectedValueException( 'Redis instance #'. $redisId .' is not defined' );

                $server = array(
                    'port' => static::$ports[$redisId],
                );
                if(!isset( static::$redises[$redisId] )){
                    $hosts = file( ROOT . \Cerceau\Config\Constants::REDIS_CONFIGS .'redis_'. $redisId );
                    if(!$hosts )
                        throw new \UnexpectedValueException( 'No config for redis instance #'. $redisId );

                    foreach( $hosts as $hostStatus ){
                        if( $hostStatus ){
                            list( $host, $status ) = explode( ' ', trim( $hostStatus ), 2 );
                            if( $status === 'master' ){
                                $server['host'] = $host;
                                break;
                            }
                        }
                    }
                }

                $this->instances[$spotId] = new \Predis\Client( $server );
            }
            return $this->instances[$spotId];
        }
	}