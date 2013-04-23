<?php
    namespace Cerceau\Script\Cron;

    abstract class ParseBase extends \Cerceau\Script\Base {

        const API_LOCATION = 'api.instagram.com';

        /**
         * @var \Cerceau\Database\I\IDatabase
         */
        protected $Database;

        /**
         * @var \Cerceau\Data\User\Bot
         */
        protected $Bot;

        protected static $botType = \Cerceau\Data\User\Bot::PARSING;

        private
            $lastApiQuery,
            $lastApiError
        ;

        public function __construct(){
            $this->Database = $this->db( 'main' );
            $this->Bot = new \Cerceau\Data\User\Bot();
            if(!$this->Bot->loadByType( static::$botType ))
                $this->Bot = false;
        }

        protected function lastQuery(){
            return $this->lastApiQuery;
        }

        protected function lastError(){
            return $this->lastApiError;
        }

        protected function api( $uri , $get = array() , $post = array() ){
            if(!$this->Bot ){
                $this->lastApiError = 'Bot capacity is not enough';
                \Cerceau\System\Registry::instance()->Logger()->log(
                    'parse-errors',
                    get_class($this) .' '. $this->lastApiError ."\n"
                );
                return false;
            }
            do {
                if( $this->Bot['request_available'] < 50 ){
                    $this->Bot->update();
                }
                else{
                    $this->lastApiQuery = 'https://'. self::API_LOCATION .'/'. $uri .'?'. http_build_query( $get );
                    $response = $this->Bot->api( $uri, $get, $post );
                    if(!$response ){
                        $this->lastApiError = 'No response';
                        \Cerceau\System\Registry::instance()->Logger()->log(
                            'parse-errors',
                            get_class($this) .' No response for: '. $this->lastApiQuery ."\n"
                        );
                        return false;
                    }
                    if( $response['meta']['code'] != 200 ){
                        $this->lastApiError = $response['meta']['code'] .': '. $response['meta']['error_message'];
                        \Cerceau\System\Registry::instance()->Logger()->log(
                            'parse-errors',
                            get_class($this) .' '. $this->lastApiError
                                ."\n". $this->lastApiQuery
                                ."\n". json_encode( $response ) ."\n\n"
                        );
                        return false;
                    }
                    if(!isset( $response['data'] )){
                        $this->lastApiError = 'No data';
                        \Cerceau\System\Registry::instance()->Logger()->log(
                            'parse-errors',
                            get_class($this) .' No data for: '. $this->lastApiQuery ."\n"
                        );
                        return false;
                    }
                    return $response;
                }
            } while( $this->Bot->loadByType( static::$botType ));
            return false;
        }
    }
