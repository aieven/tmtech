<?php
    namespace Cerceau\Script\Cron\Queues;

    class ParsePeopleAndBrandsPublicsScript extends QueueBase {

        protected static $queueName = 'Admin\\PeopleAndBrands\\PublicsAddQueue';

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
            $Public = new \Cerceau\Data\Admin\PeopleAndBrands\Publics();
            if( $this->parsePublic( $Public, $item )){
                if(!$Public->updateParsedPublic())
                    $Logger->log( 'queue-errors', 'Public '. $item['username'] .' has not updated' );
            }
            else {
                $Logger->log( 'queue-errors', 'Public '. $item['username'] .' has not parsed' );
                $Public->fetch( $item );
                $Public->updateNotParsedPublic();
            }
        }

        protected function parsePublic( \Cerceau\Data\Admin\PeopleAndBrands\Publics $Public, array $public ){
            $response = $this->Bot->api( 'v1/users/search' , array( 'q' => $public['username'] ));
            if( empty( $response['data'] ))
                return false;
            if( $public['username'] !== $response['data'][0]['username'] )
                return false;

            $instagramId = $response['data'][0]['id'];
            $response = $this->Bot->api( 'v1/users/'. $instagramId );
            if( empty( $response['data'] ))
                return false;

            $Public->fetch(
                $public + array(
                    'instagram_id' => $instagramId,
                    'followers' => $response['data']['counts']['followed_by'],
                ) + $response['data']
            );
            return true;
        }
    }
