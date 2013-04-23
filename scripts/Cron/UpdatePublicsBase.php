<?php
    namespace Cerceau\Script\Cron;

    class UpdatePublicsBase extends \Cerceau\Script\Cron\ParseBase {

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
            $publics = $this->Model->selectParsed();
            foreach( $publics as $public ){
                //----------------------------------------- update public stats ---------------------------------------
                $response = $this->api( 'v1/users/'. $public['instagram_id'] );
                if(!$response )
                    return;

                $this->Model->setFollowers( array(
                    'followers' => $response['data']['counts']['followed_by'],
                    'public_id' => $public['public_id'],
                ));

                //----------------------------------------- update and delete parsed media ----------------------------
                $response = $this->api( 'v1/users/'. $public['instagram_id'] .'/media/recent' , array(
                    'count' => 60,
                    'max_timestamp' => $public['max_datetime'] + 1,
                ));
                if(!$response )
                    return;

                $updatedMedia = array();
                foreach( $response['data'] as $data ){
                    $this->Model->updateMedia( array(
                        'instagram_media_id' => $data['id'],
                        'likes' => $data['likes']['count'],
                        'comments' => $data['comments']['count'],
                        'data' => serialize( $data ),
                    ));
                    $updatedMedia[] = $data['id'];
                }

                $lastMedia = $this->Model->selectLastMedia( $public );
                $this->Model->deleteMedia( array( 'instagram_media_ids' => array_diff( $lastMedia, $updatedMedia )));

                //----------------------------------------- parse new media -------------------------------------------
                $media = array();
                $maxId = 0;
                do {
                    $response = $this->api( 'v1/users/'. $public['instagram_id'] .'/media/recent' , array(
                        'count' => 60,
                        'min_timestamp' => $public['max_datetime'] + 1,
                        'max_id' => $maxId ? : '',
                    ));
                    if(!$response )
                        return;

                    $maxId = empty( $response['pagination'] ) ? 0 : $response['pagination']['next_max_id'];

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
                } while( $maxId );
                if( $media ){
                    $this->Model->createMedia( array( 'media' => $media ));
                    $this->Model->updateStatistics( array( 'public_id' => $public['public_id'] ));
                }
            }
        }
    }