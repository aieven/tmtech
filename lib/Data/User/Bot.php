<?php
    namespace Cerceau\Data\User;

	class Bot extends \Cerceau\Data\Base\DbRow {

        protected static $modelName = 'PostgreSQL\\User\\Bot';
        protected static $db = 'main';
        protected static $table = 'bots';

        const
            PARSING = 1,
            SMM     = 2,

            API_LOCATION = 'api.instagram.com';

        protected static $fieldsOptions = array(
            'instagram_id' => array(
                'Int',
                'load',
            ),
            'instagram_token' => array(
                'Scalar',
            ),
            'bot_name' => array(
                'Scalar',
            ),
            'request_available' => array(
                'Int',
                'default' => 5000,
            ),
            'types' => array(
                'Set',
                'types' => array(
                    self::PARSING => 'parsing',
                    self::SMM => 'smm',
                ),
            ),
            'last_update' => array(
                'Int',
            ),
        );

        public static $botTypes = array(
            self::PARSING => 'Parsing',
            self::SMM => 'Smm',
        );

        /**
         * @var \Cerceau\Model\PostgreSQL\User\Bot $Model
         */
        protected $Model;

        public function initialize(){
            $this->Model = new \Cerceau\Model\PostgreSQL\User\Bot( 'main' , 'bots' );
        }

        /**
         * @param int $type
         * @return bool
         */
        public function loadByType( $type = self::PARSING ){
            $bestBot = $this->Model->selectBot( array( 'type' => $type, ) );
            $bestBot = reset( $bestBot );
            return $this->load( $bestBot );
        }


        public function api( $uri, array $get = array(), array $post = array()){
            if(!$this['instagram_token'] )
                throw new \Cerceau\Exception\Authorize();

            $get['access_token'] = $this['instagram_token'];

            $response = \Cerceau\System\Registry::instance()->Curl()->apiQuery( self::API_LOCATION, $uri, $get, $post );
            if( preg_match( '#X\-Ratelimit\-Remaining\:\s+(\d+)#', $response['headers'] , $match ))
                $this['request_available'] = $match[1];

            return $response['body'];
        }
    }
