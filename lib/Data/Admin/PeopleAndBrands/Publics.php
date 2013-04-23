<?php
    namespace Cerceau\Data\Admin\PeopleAndBrands;

    class Publics extends \Cerceau\Data\Base\Row {

        protected static $fieldsOptions = array(
            'partition' => array(
                'Scalar',
            ),
            'public_id' => array(
                'Int',
                'load',
            ),
            'instagram_id' => array(
                'Int',
            ),
            'username' => array(
                'Scalar',
            ),
            'profile_picture' => array(
                'Scalar',
                'Default' => 'http://images.instagram.com/profiles/anonymousUser.jpg',
            ),
            'full_name' => array(
                'Scalar',
            ),
            'subcat_id' => array(
                'Int',
            ),
            'cat_id' => array(
                'Int',
            ),

            'followers' => array(
                'Int',
                'Default' => 0,
            ),
        );

        /**
         * @var \Cerceau\Model\PostgreSQL\Admin\PeopleAndBrands\Publics
         */
        protected $Model;

        public function initialize(){
            $this->Model = new \Cerceau\Model\PostgreSQL\Admin\PeopleAndBrands\Publics( 'main' );
        }

        public function addPublic(){
            $addedPublic = $this->Model->addPublic( $this->export());
            if(!$addedPublic[0]['public_id'] )
                throw new \Cerceau\Exception\Client( 'forms.public.alreadyExists' );

            $PushQueue = new \Cerceau\Data\Admin\PeopleAndBrands\PublicsAddQueue();
            $PushQueue->fetch( $addedPublic[0] + array(
                'username' => $this['username'],
                'partition' => $this['partition'],
            ));
            $PushQueue->push();
            return $addedPublic;
        }

        public function tryParseAgain(){
            $load = $this->Model->loadPublic( $this->export());
            if(!$load )
                return false;
            $PushQueue = new \Cerceau\Data\Admin\PeopleAndBrands\PublicsAddQueue();
            $PushQueue->fetch( reset( $load ));
            $PushQueue['partition'] = $this['partition'];
            return $PushQueue->push();
        }

        public function updatePublicFullName(){
            return $this->Model->updatePublic( $this->export());
        }

        public function updateParsedPublic(){
            return $this->Model->updateParsedPublic( $this->export());
        }

        public function updateNotParsedPublic(){
            return $this->Model->updateNotParsedPublic( $this->export());
        }

        public function deletePublic(){
            return $this->Model->deletePublic( $this->export());
        }
    }
