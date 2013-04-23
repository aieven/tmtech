<?php
    namespace Cerceau\Test\Snapshot;

    class ApiTestCase extends \Cerceau\Test\User\Base {

        /**
         * @var \Cerceau\Test\Utilities\Request
         */
        protected $Request;

        protected $snapshot = array(
            'snapshot_data' => 'a:3:{s:6:"charts";a:4:{i:0;i:21452692;i:1;i:21452690;i:2;i:214223212;i:3;i:21752690;}s:7:"banners";a:1:{i:0;a:2:{s:4:"link";s:17:"www.instagram.com";s:3:"img";s:26:"img/snapshot/banners/2.png";}}s:5:"tiles";a:3:{i:0;a:3:{s:9:"tile_type";i:1;s:4:"link";s:10:"www.vk.com";s:3:"img";s:24:"img/snapshot/tiles/3.png";}i:1;a:3:{s:9:"tile_type";i:2;s:4:"link";s:6:"121212";s:3:"img";s:24:"img/snapshot/tiles/4.png";}i:2;a:3:{s:9:"tile_type";i:2;s:4:"link";s:9:"123123123";s:3:"img";s:24:"img/snapshot/tiles/5.png";}}}',
            'published' => 1,
        );

        protected $publics = array(
            0 => array(
                'instagram_id' => 21452692,
                'profile_picture' => "img1",
                'username' => 'username1',
                'full_name' => 'full_name1',
            ),
            1 => array(
                'instagram_id' => 21752690,
                'profile_picture' => 'img2',
                'username' => 'username2',
                'full_name' => 'full_name2',
            ),
            2 => array(
                'instagram_id' => 214223212,
                'profile_picture' => 'img3',
                'username' => 'username3',
                'full_name' => 'full_name3',
            ),
            3 => array(
                'instagram_id' => 21452690,
                'profile_picture' => 'img4',
                'username' => 'username4',
                'full_name' => 'full_name4',
            )
        );

        public function setUp(){
            parent::setUp();
            $this->Request = new \Cerceau\Test\Utilities\Request( false );
        }

        public function testSnapshot(){
            $domain = \Cerceau\System\Registry::instance()->DomainConfig()->sub( 'img' );

            $this->db( 'main')->query(<<<SQL
        INSERT INTO {{ t('snapshots') }} ( snapshot_data, published )
        VALUES ( {{ s(snapshot_data)}}, {{ i(published) }} )
SQL
            ,$this->snapshot
);

            foreach( $this->publics as $public ){

                $this->db( 'main')->query(<<<SQL
        INSERT INTO {{ t('people_publics') }} ( instagram_id, profile_picture, username, full_name )
        VALUES ( {{ i(instagram_id)}}, {{ s(profile_picture) }}, {{ s(username) }}, {{ s(full_name) }} )
SQL
                    ,$public
                );

            }

            $response = $this->Request->get(
                'api\\Snapshot',
                'snapshot',
                array()
            );
            $this->assertEqual( $response['result'], 'ok' );

            $snapshot = $response['main'];
            $this->assertEqual( count( $snapshot['charts']), 4 );
            $this->assertEqual( $snapshot['charts'][0]['instagram_id'], $this->publics[0]['instagram_id'] );
            $this->assertEqual( $snapshot['charts'][0]['username'], $this->publics[0]['username'] );
            $this->assertEqual( $snapshot['charts'][0]['profile_picture'], $this->publics[0]['profile_picture'] );
            $this->assertEqual( $snapshot['charts'][0]['full_name'], $this->publics[0]['full_name'] );

            $this->assertEqual( $snapshot['charts'][1]['instagram_id'], $this->publics[3]['instagram_id'] );
            $this->assertEqual( $snapshot['charts'][2]['instagram_id'], $this->publics[2]['instagram_id'] );
            $this->assertEqual( $snapshot['charts'][3]['instagram_id'], $this->publics[1]['instagram_id'] );

            $this->assertEqual( count( $snapshot['top_banners']), 1 );
            $top_banner = $snapshot['top_banners'][0]['img'];
            $top_banner = explode( '/', $top_banner );
            $top_banner = end( $top_banner );

            $this->assertEqual( count( $snapshot['bottom_banners']), 3 );

            $this->assertEqual( '2.png', $top_banner );

            $bottom_banner = $snapshot['bottom_banners'][0]['img'];
            $bottom_banner = explode( '/', $bottom_banner );
            $bottom_banner = end( $bottom_banner );

            $this->assertEqual( '3.png', $bottom_banner );

            $bottom_banner = $snapshot['bottom_banners'][1]['img'];
            $bottom_banner = explode( '/', $bottom_banner );
            $bottom_banner = end( $bottom_banner );

            $this->assertEqual( '4.png', $bottom_banner );
        }
    }
