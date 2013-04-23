<?php
    namespace Cerceau\Test\PeopleAndBrands;

    class PublicsTestCase extends \Cerceau\Test\User\Base {

        /**
         * @var \Cerceau\Test\Utilities\Request
         */
        protected $Request;

        protected $peoplePublics = array(
            0 => array(
                'partition' => 'people',
                'cat_id'    => '1',
                'subcat_id' => '232',
                'instagram_id' => '143654695',
                'username'  => 'soberzerg',
                'profile_picture' => 'http://images.instagram.com/profiles/anonymousUser1.jpg',
                'full_name' => 'Sergej Sobolev',
                'followers' => '2342',
                'likes' => '4566',
                'comments' => '2112',
                'photos' => '22132',
            ),
            1 => array(
                'partition' => 'people',
                'cat_id'    => '1',
                'subcat_id' => '232',
                'instagram_id' => '543644625',
                'username'  => 'qwdqwdq',
                'profile_picture' => 'http://images.instagram.com/profileqdws/anonymousUser1.jpg',
                'full_name' => 'Aasdsad Psaddasd',
                'followers' => '1233',
                'likes' => '123',
                'comments' => '422112',
                'photos' => '221232',
            ),
        );

        protected static $brandsPublic = array(
            'partition' => 'brands',
            'cat_id'    => '1',
            'subcat_id' => '232',
            'instagram_id' => '44755832',
            'username'  => 'aieven',
            'profile_picture' => 'http://images.instagram.com/profiles/anonymousUser2.jpg',
            'full_name' => 'Oleg Nalimov'
        );

        protected static $unparsedPeoplePublic = array(
            'partition' => 'people',
            'cat_id'    => '1',
            'subcat_id' => '232',
            'username'  => 'aieven',
            'profile_picture' => 'http://images.instagram.com/profiles/anonymousUser3.jpg',
        );
        public function setUp(){
            parent::setUp();
            $this->Request = new \Cerceau\Test\Utilities\Request( false );
        }

        public function testPublics(){

            foreach( $this->peoplePublics as $public ){

                $this->db( 'main')->selectRecord(<<<SQL
            INSERT INTO {{ t('people_publics') }} (
                cat_id, subcat_id, instagram_id, username, profile_picture,
                full_name, followers, likes, comments, photos
              )
            VALUES (
              {{ i(cat_id) }},
              {{ i(subcat_id) }},
              {{ i(instagram_id)}},
              {{ s(username) }},
              {{ s(profile_picture)}},
              {{ s(full_name) }},
              {{ i(followers) }},
              {{ i(likes) }},
              {{ i(comments) }},
              {{ i(photos) }}
            )
            RETURNING public_id
SQL
                    ,$public
                );
            }

            $Publics = new \Cerceau\Data\Admin\PeopleAndBrands\Catalog();
            $Publics['use_method'] = 1;
            $Publics->saveMethod();

            $response = $this->Request->get(
                'api\\PeopleAndBrands',
                'subcategoryPublics',
                array(
                    'partition' => $this->peoplePublics[0]['partition'],
                    'cat_id'    => $this->peoplePublics[0]['cat_id'],
                    'subcat_id' => $this->peoplePublics[0]['subcat_id'],
                )
            );
            $this->assertEqual( $response['result'], 'ok' );
            $this->assertEqual( count( $response['publics'] ), 2 );
            $response = reset( $response );

            $this->assertEqual( $response[0]['instagram_id'], $this->peoplePublics[1]['instagram_id'] );
            $this->assertEqual( $response[0]['username'], $this->peoplePublics[1]['username'] );
            $this->assertEqual( $response[0]['profile_picture'], $this->peoplePublics[1]['profile_picture'] );
            $this->assertEqual( $response[0]['full_name'], $this->peoplePublics[1]['full_name'] );
            $this->assertEqual( $response[0]['followers'], $this->peoplePublics[1]['followers'] );
            $this->assertEqual( $response[0]['likes'], $this->peoplePublics[1]['likes'] );
            $this->assertEqual( $response[0]['comments'], $this->peoplePublics[1]['comments'] );
            $this->assertFalse( array_key_exists( 'photos', $response[0] ));

            $response = $this->Request->get(
                'api\\PeopleAndBrands',
                'search',
                array(
                     'partition' => $this->peoplePublics[0]['partition'],
                ),
                array(
                     'full_name' => 'se'
                )
            );
            $this->assertEqual( $response['result'], 'ok' );
            $this->assertEqual( count( $response['publics'] ), 1 );
            $response = reset( $response );
            $this->assertEqual( $response[0]['instagram_id'], $this->peoplePublics[0]['instagram_id'] );
            $this->assertEqual( $response[0]['username'], $this->peoplePublics[0]['username'] );
            $this->assertEqual( $response[0]['profile_picture'], $this->peoplePublics[0]['profile_picture'] );
            $this->assertEqual( $response[0]['full_name'], $this->peoplePublics[0]['full_name'] );
            $this->assertEqual( $response[0]['followers'], $this->peoplePublics[0]['followers'] );
            $this->assertEqual( $response[0]['likes'], $this->peoplePublics[0]['likes'] );
            $this->assertEqual( $response[0]['comments'], $this->peoplePublics[0]['comments'] );
            $this->assertFalse( array_key_exists( 'photos', $response[0] ));

            $response = $this->Request->get(
                'api\\PeopleAndBrands',
                'search',
                array(
                     'partition' => $this->peoplePublics[0]['partition'],
                ),
                array(
                     'full_name' => 'sobo'
                )
            );

            $this->assertEqual( $response['result'], 'ok' );
            $this->assertEqual( count( $response['publics'] ), 1 );
            $response = reset( $response );
            $this->assertEqual( $response[0]['instagram_id'], $this->peoplePublics[0]['instagram_id'] );
            $this->assertEqual( $response[0]['username'], $this->peoplePublics[0]['username'] );
            $this->assertEqual( $response[0]['profile_picture'], $this->peoplePublics[0]['profile_picture'] );
            $this->assertEqual( $response[0]['full_name'], $this->peoplePublics[0]['full_name'] );
            $this->assertEqual( $response[0]['followers'], $this->peoplePublics[0]['followers'] );
            $this->assertEqual( $response[0]['likes'], $this->peoplePublics[0]['likes'] );
            $this->assertEqual( $response[0]['comments'], $this->peoplePublics[0]['comments'] );
            $this->assertFalse( array_key_exists( 'photos', $response[0] ));

            $response = $this->Request->get(
                'api\\PeopleAndBrands',
                'search',
                array(
                     'partition' => $this->peoplePublics[0]['partition'],
                ),
                array(
                     'full_name' => 'obo'
                )
            );

            $this->assertEqual( $response['result'], 'ok' );
            $this->assertEqual( count( $response['publics'] ), 0 );


            $id = $this->db( 'main')->selectRecord(<<<SQL
        INSERT INTO {{ t('brands_publics') }} (
            cat_id, subcat_id, instagram_id, username, profile_picture, full_name
          )
        VALUES (
          {{ i(cat_id) }},
          {{ i(subcat_id) }},
          {{ i(instagram_id)}},
          {{ s(username) }},
          {{ s(profile_picture)}},
          {{ s(full_name) }}
        )
        RETURNING public_id
SQL
                ,self::$brandsPublic
            );
            $id = reset( $id );
            $response = $this->Request->get(
                'api\\PeopleAndBrands',
                'subcategoryPublics',
                array(
                     'partition' => self::$brandsPublic['partition'],
                     'subcat_id' => self::$brandsPublic['subcat_id'],
                     'cat_id'    => self::$brandsPublic['cat_id'],
                )
            );
            $this->assertEqual( $response['result'], 'ok' );
            $this->assertEqual( count( $response['publics'] ), 1 );
            $response = reset( $response );
            $this->assertEqual( $response[0]['public_id'], $id['public_id'] );
            $this->assertEqual( $response[0]['username'], self::$brandsPublic['username'] );
            $this->assertEqual( $response[0]['profile_picture'], self::$brandsPublic['profile_picture'] );
            $this->assertEqual( $response[0]['full_name'], self::$brandsPublic['full_name'] );
            $this->assertEqual( $response[0]['instagram_id'], self::$brandsPublic['instagram_id'] );
            $this->assertTrue( array_key_exists( 'followers', $response[0] ));

            $id = $this->db( 'main')->selectRecord(<<<SQL
        INSERT INTO {{ t('people_publics') }} ( cat_id, subcat_id, username, profile_picture )
        VALUES ( {{ i(cat_id) }}, {{ i(subcat_id) }}, {{ s(username) }}, {{ s(profile_picture)}} )
        RETURNING public_id
SQL
                ,self::$unparsedPeoplePublic
            );
            $id = reset( $id );
            $response = $this->Request->get(
                'api\\PeopleAndBrands',
                'subcategoryPublics',
                array(
                     'partition' => self::$unparsedPeoplePublic['partition'],
                     'subcat_id' => self::$unparsedPeoplePublic['subcat_id'],
                     'cat_id'    => self::$unparsedPeoplePublic['cat_id'],
                )
            );
            $this->assertEqual( $response['result'], 'ok' );
            $this->assertEqual( count( $response['publics'] ), 2 );
            $response = reset( $response );
            $this->assertNotEqual( $response[0]['public_id'], $id['public_id'] );
            $this->assertNotEqual( $response[0]['username'], self::$unparsedPeoplePublic['username'] );
            $this->assertNotEqual( $response[0]['profile_picture'], self::$unparsedPeoplePublic['profile_picture'] );

        }
    }
