<?php
    namespace Cerceau\Test\Gallery;

    class PublicsTestCase extends \Cerceau\Test\User\Base {

        /**
         * @var \Cerceau\Test\Utilities\Request
         */
        protected $Request;
        protected $galleryPublics = array(
            0 => array(
                'public_id' => '1',
                'gallery_id' => '1',
                'instagram_id' => 10206720,
                'username' => 'barackobama',
                'full_name' => 'Barack Obama',
                'profile_picture' => 'http://images.instagram.com/profiles/profile_10206720_75sq_1325635414.jpg',
            ),
            1 => array(
                'public_id' => '2',
                'gallery_id' => '2',
                'instagram_id' => 2323232,
                'username' => 'georjebush',
                'full_name' => 'George Bush',
                'profile_picture' => 'http://images.instagram.com/profiles/profile_10206720_75sq_1325635414.jpg',
            ),
            2 => array(
                'public_id' => '3',
                'gallery_id' => '1',
                'instagram_id' => 23232324,
                'username' => 'Clinton',
                'full_name' => 'Hilary',
                'profile_picture' => 'http://images.instagram.com/profiles/profile_10206720_75sq_1325635414.jpg',
            )
        );

        protected $publicMedias = array(
            0 => array(
                'instagram_id' => '12',
                'comments' => 10,
                'likes' => 20,
                'datetime' => 1352246413,
                'public_id' => '1',
                'deleted' => 'f',
                'data' => array(
                    'id' => '12',
                ),
            ),
            1 => array(
                'instagram_id' => '13',
                'comments' => 11,
                'likes' => 21,
                'datetime' => 1352246412,
                'public_id' => '1',
                'deleted' => 'f',
                'data' => array(
                    'id' => '13',
                ),
            ),
            2 => array(
                'instagram_id' => '14',
                'comments' => 12,
                'likes' => 22,
                'datetime' => 1352246414,
                'public_id' => '1',
                'deleted' => 'f',
                'data' => array(
                    'id' => '14',
                ),
            ),
            3 => array(
                'instagram_id' => '10',
                'comments' => 12,
                'likes' => 22,
                'datetime' => 1352241231,
                'public_id' => '2',
                'deleted' => 'f',
                'data' => array(
                    'id' => '10',
                ),
            ),
            4 => array(
                'instagram_id' => '15',
                'comments' => 12,
                'likes' => 22,
                'datetime' => 1352246410,
                'public_id' => '1',
                'deleted' => 't',
                'data' => array(
                    'id' => '15',
                ),
            ),
        );

        public function setUp(){
            parent::setUp();
            $this->Request = new \Cerceau\Test\Utilities\Request( false );
        }

        public function testGalleryPublicsMedia(){
            foreach( $this->galleryPublics as $public ){

                $this->db( 'main')->query(<<<SQL
        INSERT INTO {{ t('gallery_publics') }} ( gallery_id, instagram_id, username, profile_picture, full_name )
        VALUES ( {{ i(gallery_id) }}, {{ i(instagram_id) }}, {{ s(username) }}, {{ s(profile_picture) }}, {{ s(full_name) }} )
SQL
                    ,$public
                );
            }

            foreach( $this->publicMedias as $media ){
                $media['data'] = serialize($media['data']);
                $this->db( 'main')->query(<<<SQL
        INSERT INTO {{ t('media') }} ( instagram_media_id, comments_count, likes_count, datetime, public_id, data )
        VALUES ( {{ i(instagram_id) }}, {{ i(comments) }}, {{ i(likes) }}, {{ i(datetime) }}, {{ i(public_id) }}, {{ s(data) }} )
SQL
                    ,$media
                );
            }
            $response = $this->Request->get(
                'api\\Gallery',
                'media',
                array(
                     'gallery_id' => $this->galleryPublics[0]['gallery_id'],
                )
            );
            $this->assertEqual( $response['result'], 'ok' );
            $media = $response['media'];
            $this->assertEqual( count( $media ), 4 );
            $this->assertEqual( $media[0]['id'], $this->publicMedias[2]['instagram_id'] );
            $this->assertEqual( $media[1]['id'], $this->publicMedias[0]['instagram_id'] );
            $this->assertEqual( $media[2]['id'], $this->publicMedias[1]['instagram_id'] );
            $this->assertEqual( $media[3]['id'], $this->publicMedias[4]['instagram_id'] );

            $response = $this->Request->get(
                'api\\Gallery',
                'media',
                array(
                    'gallery_id' => $this->galleryPublics[0]['gallery_id'],
                ),
                array(
                    'limit' => 2,
                    'max_datetime' => $this->publicMedias[2]['datetime'],
                )
            );

            $this->assertEqual( $response['result'], 'ok' );
            $media = $response['media'];
            $this->assertEqual( count( $media ), 2 );
            $this->assertEqual( $media[0]['id'], $this->publicMedias[0]['instagram_id'] );
            $this->assertEqual( $media[1]['id'], $this->publicMedias[1]['instagram_id'] );

            $response = $this->Request->get(
                'api\\Gallery',
                'media',
                array(
                    'gallery_id' => $this->galleryPublics[0]['gallery_id'],
                ),
                array(
                    'limit' => 2,
                    'min_datetime' => $this->publicMedias[1]['datetime'],
                )
            );
            $this->assertEqual( $response['result'], 'ok' );
            $media = $response['media'];
            $this->assertEqual( count( $media ), 2 );
            $this->assertEqual( $media[0]['id'], $this->publicMedias[2]['instagram_id'] );
            $this->assertEqual( $media[1]['id'], $this->publicMedias[0]['instagram_id'] );


            $response = $this->Request->get(
                'api\\Gallery',
                'publics',
                array(
                    'gallery_id' => $this->galleryPublics[0]['gallery_id'],
                )
            );
            $this->assertEqual( $response['result'], 'ok' );
            $publics = $response['publics'];
            $this->assertEqual( count( $publics ), 2 );
        }
    }
