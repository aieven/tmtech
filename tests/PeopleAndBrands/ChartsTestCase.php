<?php
    namespace Cerceau\Test\PeopleAndBrands;

    class ChartsTestCase extends \Cerceau\Test\User\Base {

        /**
         * @var \Cerceau\Test\Utilities\Request
         */
        protected $Request;

        protected $publics = array(
            0 => array(
                'instagram_id' => 1,
                'profile_picture' => "img1",
                'username' => 'username1',
                'full_name' => 'full_name1',
                'followers' => 2,
                'likes' => 234,
                'comments' => 312,
                'photos' => 998
            ),
            1 => array(
                'instagram_id' => 2,
                'profile_picture' => 'img2',
                'username' => 'username2',
                'full_name' => 'full_name2',
                'followers' => 3,
                'likes' => 23214,
                'comments' => 312312,
                'photos' => 91238
            ),
            2 => array(
                'instagram_id' => 3,
                'profile_picture' => 'img3',
                'username' => 'username3',
                'full_name' => 'full_name3',
                'followers' => 4,
                'likes' => 2134,
                'comments' => 322,
                'photos' => 938
            ),
            3 => array(
                'instagram_id' => 4,
                'profile_picture' => 'img4',
                'username' => 'username4',
                'full_name' => 'full_name4',
                'followers' => 1,
                'likes' => 214,
                'comments' => 122,
                'photos' => 548
            ),
        );

        public function setUp(){
            parent::setUp();
            $this->Request = new \Cerceau\Test\Utilities\Request( false );
        }

        public function testChart(){

            foreach( $this->publics as $public ){

                $this->db( 'main')->query(<<<SQL
        INSERT INTO {{ t('people_publics') }}
          ( instagram_id, profile_picture, username, full_name, followers, likes, comments, photos )
        VALUES (
          {{ i(instagram_id)}},
          {{ s(profile_picture) }},
          {{ s(username) }},
          {{ s(full_name) }},
          {{ i(followers) }},
          {{ i(likes) }},
          {{ i(comments) }},
          {{ i(photos) }}
        );
SQL
                    ,$public
                );

            }

            $this->db( 'main')->query(<<<SQL
        INSERT INTO {{ t('people_publics') }} (  profile_picture, username )
        VALUES ( {{ s('test') }}, {{ s('test') }} )
SQL
            );

            $response = $this->Request->get(
                'api\\PeopleAndBrands',
                'charts',
                array()
            );
            $this->assertEqual( $response['result'], 'ok' );
            $this->assertEqual( $response['use_method'], 0 );
            $public = $response['publics'];
            $this->assertEqual( count( $public ), 4 );
            $this->assertEqual( $public[0]['instagram_id'], $this->publics[2]['instagram_id'] );
            $this->assertEqual( $public[0]['profile_picture'], $this->publics[2]['profile_picture'] );
            $this->assertEqual( $public[0]['username'], $this->publics[2]['username'] );
            $this->assertEqual( $public[0]['full_name'], $this->publics[2]['full_name'] );
            $this->assertEqual( $public[0]['followers'], $this->publics[2]['followers'] );
            $this->assertEqual( $public[0]['likes'], $this->publics[2]['likes'] );
            $this->assertEqual( $public[0]['comments'], $this->publics[2]['comments'] );
            $this->assertEqual( $public[0]['photos'], $this->publics[2]['photos'] );
            $this->assertFalse( array_key_exists( 'deleted', $public[0] ));
            $this->assertFalse( array_key_exists( 'parsed_all_old_media', $public[0] ));

            $this->assertEqual( $public[1]['instagram_id'], $this->publics[1]['instagram_id'] );
            $this->assertEqual( $public[2]['instagram_id'], $this->publics[0]['instagram_id'] );
            $this->assertEqual( $public[3]['instagram_id'], $this->publics[3]['instagram_id'] );

            $response = $this->Request->get(
                'api\\PeopleAndBrands',
                'charts',
                array(),
                array(
                    'limit' => 2,
                    'instagram_id' => 4
                )
            );
            $this->assertEqual( $response['result'], 'ok' );
            $this->assertEqual( $response['use_method'], 0 );
            $public = $response['publics'];
            $this->assertEqual( count( $public ), 2 );
            $this->assertEqual( $public[0]['instagram_id'], $this->publics[2]['instagram_id'] );
            $this->assertEqual( $public[0]['profile_picture'], $this->publics[2]['profile_picture'] );
            $this->assertEqual( $public[0]['username'], $this->publics[2]['username'] );
            $this->assertEqual( $public[0]['full_name'], $this->publics[2]['full_name'] );
            $this->assertEqual( $public[0]['followers'], $this->publics[2]['followers'] );
            $this->assertEqual( $public[0]['likes'], $this->publics[2]['likes'] );
            $this->assertEqual( $public[0]['comments'], $this->publics[2]['comments'] );
            $this->assertEqual( $public[0]['photos'], $this->publics[2]['photos'] );
            $this->assertFalse( array_key_exists( 'deleted', $public[0] ));
            $this->assertFalse( array_key_exists( 'parsed_all_old_media', $public[0] ));

            $this->assertEqual( $public[1]['instagram_id'], $this->publics[1]['instagram_id'] );
            $this->assertEqual( $public[1]['instagram_id'], $this->publics[1]['instagram_id'] );
            $this->assertEqual( $public[1]['profile_picture'], $this->publics[1]['profile_picture'] );
            $this->assertEqual( $public[1]['username'], $this->publics[1]['username'] );
            $this->assertEqual( $public[1]['full_name'], $this->publics[1]['full_name'] );
            $this->assertEqual( $public[1]['followers'], $this->publics[1]['followers'] );
            $this->assertEqual( $public[1]['likes'], $this->publics[1]['likes'] );
            $this->assertEqual( $public[1]['comments'], $this->publics[1]['comments'] );
            $this->assertEqual( $public[1]['photos'], $this->publics[1]['photos'] );
            $this->assertFalse( array_key_exists( 'deleted', $public[0] ));
            $this->assertFalse( array_key_exists( 'parsed_all_old_media', $public[0] ));
        }
    }
