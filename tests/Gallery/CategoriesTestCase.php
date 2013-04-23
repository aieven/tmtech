<?php
    namespace Cerceau\Test\Gallery;

    class CategoriesTestCase extends \Cerceau\Test\User\Base {

        /**
         * @var \Cerceau\Test\Utilities\Request
         */
        protected $Request;

        protected $categories = array(
            0 => array(
                'name' => 'category1',
                'icon' => "img/gallery/img1.png",
                'order_id' => '2',
                'published' => '1',
                'deleted' => '0',
            ),
            1 => array(
                'name' => 'category2',
                'icon' => "img/gallery/img2.png",
                'order_id' => '3',
                'published' => '1',
                'deleted' => '0',
            ),
            2 => array(
                'name' => 'category3',
                'icon' => "img/gallery/img3.png",
                'order_id' => '1',
                'published' => '1',
                'deleted' => '0',
            ),
            3 => array(
                'name' => 'category4',
                'icon' => "img/gallery/img4.png",
                'order_id' => '4',
                'published' => '0',
                'deleted' => '1',
            ),
            4 => array(
                'name' => 'category5',
                'icon' => "img/gallery/img5.png",
                'order_id' => '5',
                'published' => '1',
                'deleted' => '1',
            )
        );

        public function setUp(){
            parent::setUp();
            $this->Request = new \Cerceau\Test\Utilities\Request( false );
        }

        public function testCategories(){
            $domain = \Cerceau\System\Registry::instance()->DomainConfig()->sub( 'img' );
            foreach( $this->categories as $category){
                $this->db( 'main')->selectRecord(<<<SQL
        INSERT INTO {{ t('gal_categories') }} ( name, order_id, icon, published, deleted )
        VALUES ( {{ s(name) }}, {{ i(order_id) }}, {{ s(icon) }}, {{ i(published) }}, {{ i(deleted) }} )
SQL
                    ,$category
                );
            }
            $response = $this->Request->get(
                'api\\Gallery',
                'galleries',
                array()
            );
            $this->assertEqual( $response['result'], 'ok' );
            $categories = $response['galleries'];
            $this->assertEqual( count( $categories ), 3 );
            $this->assertEqual( $categories[0]['name'], $this->categories[2]['name'] );
            $this->assertEqual( $categories[0]['icon'], $domain . $this->categories[2]['icon'] );
            $this->assertFalse( array_key_exists( 'order_id', $categories[0] ));
            $this->assertFalse( array_key_exists( 'published', $categories[0] ));
            $this->assertFalse( array_key_exists( 'deleted', $categories[0] ));

            $this->assertEqual( $categories[1]['name'], $this->categories[0]['name'] );
            $this->assertEqual( $categories[1]['icon'], $domain . $this->categories[0]['icon'] );

            $this->assertEqual( $categories[2]['name'], $this->categories[1]['name'] );
            $this->assertEqual( $categories[2]['icon'], $domain . $this->categories[1]['icon'] );
        }
    }
