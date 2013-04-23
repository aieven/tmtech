<?php
    namespace Cerceau\Test\Utilities;

    class Entities {

        public static function createAuth( $data = array()){
            $data = $data + array(
                'email' => 'qwerty@test.mail',
                'password' => '123456',
                'privileges' => array_keys( \Cerceau\Data\User\Privileges::names()),
            );

            $Auth = new \Cerceau\Data\User\Auth();
            $Auth->fetch( $data );
            if(!$Auth->create())
                throw new \Exception();

            return $Auth;
        }

        public static function createBot( $data = array()){
            $data = $data + array(
                'instagram_id' => 777,
                'instagram_token' => '777',
                'bot_name' => 'Joseph',
                'types' => array( 1 ),
            );

            $Bot = new \Cerceau\Data\User\Bot();
            $Bot->fetch( $data );
            if( !$Bot->create())
                throw new \Exception();

            return $Bot;
        }

        public static function createPeopleAndBrandsSubcategory( $data = array()){
            $Subcategory = new \Cerceau\Data\Admin\PeopleAndBrands\Category();
            $Subcategory->fetch( $data );
            $subcat = $Subcategory->addSubcat();
            $subcat = reset( $subcat );
            return $subcat;
        }

        public static function createPeopleAndBrandsPublic( $data = array()){
            $Publics = new \Cerceau\Data\Admin\PeopleAndBrands\Publics();
            $Publics->fetch( $data );
            $public = $Publics->addPublic();
            return $public;
        }

        public static function createGalleryPublic( $data = array()){
            $Publics = new \Cerceau\Data\Admin\PeopleAndBrands\Publics();
            $Publics->fetch( $data );
            $public = $Publics->addPublic();
            return $public;
        }

        public static function createPublicMedia( $data = array()){
            $Publics = new \Cerceau\Data\Admin\PeopleAndBrands\Publics();
            $Publics->fetch( $data );
            $public = $Publics->addPublic();
            return $public;
        }

    }
