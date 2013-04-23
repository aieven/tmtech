<?php
    namespace Cerceau\Data\Admin\Gallery\Publics;

    class Publics extends \Cerceau\Data\Base\Row {

        protected static $db = 'main';

        protected function initialize(){
            self::$fieldsOptions = array(
                'gallery_id' => array(
                    'Int',
                ),
                'public_id' => array(
                    'Int',
                ),
                'instagram_id' => array(
                    'Int',
                ),
                'username' => array(
                    'Scalar',
                ),
                'full_name' => array(
                    'Scalar',
                ),
                'profile_picture' => array(
                    'Scalar',
                    'Default' => 'http://images.instagram.com/profiles/anonymousUser.jpg',
                ),
                'followers' => array(
                    'Int',
                    'Default' => 0,
                ),
                'status' => array(
                    'Scalar',
                ),
            );
            $this->Model = new \Cerceau\Model\PostgreSQL\Admin\Gallery\Publics\Publics();
            parent::initialize();
        }
        /**
         * @var \Cerceau\Model\PostgreSQL\Admin\Gallery\Publics\Publics
         */
        protected $Model;

        /**
         * @return array
         * @throws \Cerceau\Exception\Client
         */
        public function addPublic(){
            $addedPublic = $this->Model->addPublic( $this->export());
            if(!$addedPublic[0]['public_id'] )
                throw new \Cerceau\Exception\Client( 'forms.public.alreadyExists' );

            $PushQueue = new \Cerceau\Data\Admin\Gallery\PublicsParseQueue();
            $PushQueue->fetch( $addedPublic[0] + array( 'username' => $this['username'] ));
            $PushQueue->push();
            return $addedPublic;
        }

        public function tryParseAgain(){
            $load = $this->Model->loadPublic( $this->export());
            if(!$load )
                return false;
            $PushQueue = new \Cerceau\Data\Admin\Gallery\PublicsParseQueue();
            $PushQueue->fetch( reset( $load ));
            return $PushQueue->push();
        }

        public function updatePublicFullName(){
            return $this->Model->updatePublic( $this->export());
        }

        public function updateParsedPublic(){
            return $this->Model->updateParsedPublic( $this->export());
        }

        public function updateNotParsedPublic( $data ){
            return $this->Model->updateNotParsedPublic( $data );
        }

        public function addPublicPhotos( $photos ){
            return $this->Model->addPublicPhotos( array( 'media' => $photos ));
        }

        public function deletePublic(){
            return $this->Model->deletePublic( $this->export());
        }
    }
