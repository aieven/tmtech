<?php
    namespace Cerceau\Script\Cron;

    abstract class NewPublicsBase extends \Cerceau\Script\Cron\ParseBase {

        /**
         * @var \Cerceau\Model\PostgreSQL\Publics\Parsing
         */
        protected $Model;

        protected static $table;

        public function __construct(){
            parent::__construct();
            $this->Model = new \Cerceau\Model\PostgreSQL\Publics\Parsing( 'main', static::$table );
        }

        public function run(){
            $publics = $this->Model->selectNew();
            foreach( $publics as $public ){
                $response = $this->api( 'v1/users/'. $public['instagram_id'] .'/media/recent' , array(
                    'count' => 60,
                    'max_timestamp' => $public['min_datetime'],
                ));
                if(!$response )
                    continue;

                $media = array();
                foreach( $response['data'] as $data ){
                    $media[] = array(
                        'public_id' => $public['public_id'],
                        'instagram_media_id' => $data['id'],
                        'likes' => $data['likes']['count'],
                        'comments' => $data['comments']['count'],
                        'datetime' => $data['created_time'],
                        'data' => serialize( $data ),
                    );
                }
                if( $media ) {
                    $this->Model->createMedia( array( 'media' => $media ));
                    $this->Model->updateStatistics( array( 'public_id' => $public['public_id'] ));
                }

                if( empty( $response['pagination'] ))
                    $this->Model->completeParseOldMedia( $public );
            }
        }
    }