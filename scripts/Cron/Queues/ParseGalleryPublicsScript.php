<?php
    namespace Cerceau\Script\Cron\Queues;

    class ParseGalleryPublicsScript extends QueueBase {

        protected static $queueName = 'Admin\\Gallery\\PublicsParseQueue';

        /**
         * @var \Cerceau\Data\User\Bot $Bot
         */
        protected $Bot;

        public function initialize(){
            $this->Bot = new \Cerceau\Data\User\Bot();
            $this->Bot->loadByType();
        }

        protected function runQueue( array $item ){
            $Logger = \Cerceau\System\Registry::instance()->Logger();
            $response = $this->Bot->api( 'v1/users/search' , array( 'q' => $item['username'] ));
            $Public = new \Cerceau\Data\Admin\Gallery\Publics\Publics();
            if(!empty( $response['data'] )){
                if( $item['username'] !== $response['data'][0]['username'] ){
                    $Logger->log( 'queue-errors', 'Public '. $item['username'] .' not found' );
                    $Public->updateNotParsedPublic( array(
                        'public_id' => $item['public_id'],
                    ));
                    return;
                }
                $instagramId = $response['data'][0]['id'];
                $response = $this->Bot->api( 'v1/users/'. $instagramId );
                if(!empty( $response['data'] )){
                    $followers = $response['data']['counts']['followed_by'];
                    $Public->fetch(
                        array(
                            'instagram_id' => $instagramId,
                            'public_id' => $item['public_id'],
                            'followers' => $followers,
                        ) + $response['data']
                    );
                    if(!$Public->updateParsedPublic())
                        $Logger->log( 'queue-errors', 'Public '. $item['username'] .' has not updated' );
                    return;
                }
            }
            $Logger->log( 'queue-errors', 'Public '. $item['username'] .' has not parsed' );
            $Public->updateNotParsedPublic( array(
                'public_id' => $item['public_id'],
            ));

        }
    }