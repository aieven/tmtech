<?php
    namespace Cerceau\Test\PeopleAndBrands;

    class PeopleAndBrandsTestCase extends \Cerceau\Test\User\Base {

        /**
         * @var \Cerceau\Test\Utilities\Request
         */
        protected $Request;

        protected static $peopleCategory = array(
            'partition' => 'people',
            'cat_name' => 'peopleCategoryName',
            'cat_icon' => 'img/categories/icons/peopleCategoryIcon.jpg',
        );

        protected static $peopleSubcategory = array(
            'partition' => 'people',
            'subcat_name' => 'subcat name',
            'subcat_icon' => 'img/subcategories/icons/peopleSubcategoryicon.png',
        );

        protected static $brandsCategory = array(
            'partition' => 'brands',
            'cat_name' => 'peopleCategoryName',
            'cat_icon' => 'img/categories/icons/peopleCategoryIcon.jpg',
        );

        protected static $brandsSubcategory = array(
            'partition' => 'brands',
            'subcat_name' => 'subcat name',
            'subcat_icon' => 'img/subcategories/icons/brandsSubcategoryicon.jpg',
        );

        public function setUp(){
            parent::setUp();
            $this->Request = new \Cerceau\Test\Utilities\Request( false );
        }

        public function testPeopleAndBrands(){
            $domain = \Cerceau\System\Registry::instance()->DomainConfig()->sub( 'img' );

            $db = self::$peopleCategory['partition'].'_categories';
            $catName = self::$peopleCategory['cat_name'];
            $catIcon = self::$peopleCategory['cat_icon'];
            $catId = $this->Database->selectRecord(
                <<<SQL
               -- SQL_TEST
                INSERT INTO {$db} ( cat_name, cat_icon ) VALUES ( '{$catName}', '{$catIcon}' )
                RETURNING cat_id;
SQL
            );
            $catId = reset( $catId );

            $subcat = \Cerceau\Test\Utilities\Entities::createPeopleAndBrandsSubcategory(
                self::$peopleSubcategory + $catId );

            $response = $this->Request->get(
                'api\\PeopleAndBrands',
                'categories',
                array( 'partition' => self::$peopleCategory['partition'] )
            );
            $this->assertEqual( $response['result'], 'ok' );
            $this->assertEqual( count( $response['categories'] ), 1 );

            $response = reset( $response );
            $this->assertEqual( $response[0]['cat_id'], $catId['cat_id'] );
            $this->assertEqual( $response[0]['cat_name'], self::$peopleCategory['cat_name'] );
            $this->assertEqual( $response[0]['cat_icon'], $domain . self::$peopleCategory['cat_icon'] );
            $this->assertFalse( isset( $response['publics_count'] ));
            $this->assertEqual( count( $response[0]['sub'] ), 1 );
            $this->assertEqual( $response[0]['sub'][0]['subcat_id'], $subcat['subcat_id'] );
            $this->assertEqual( $response[0]['sub'][0]['subcat_name'], self::$peopleSubcategory['subcat_name'] );
            $this->assertEqual( $response[0]['sub'][0]['subcat_icon'], $domain . self::$peopleSubcategory['subcat_icon'] );
            $this->assertFalse( isset( $response[0]['sub'][0]['deleted'] ));

            $db = self::$brandsCategory['partition'].'_categories';
            $catName = self::$brandsCategory['cat_name'];
            $catIcon = self::$brandsCategory['cat_icon'];
            $catId = $this->Database->selectRecord(
                <<<SQL
               -- SQL_TEST
                INSERT INTO {$db} ( cat_name, cat_icon ) VALUES ( '{$catName}', '{$catIcon}' )
                RETURNING cat_id;
SQL
           );
            $catId = reset( $catId );

            $subcat = \Cerceau\Test\Utilities\Entities::createPeopleAndBrandsSubcategory(
                self::$brandsSubcategory + $catId );

            $response = $this->Request->get(
                'api\\PeopleAndBrands',
                'categories',
                array( 'partition' => self::$brandsCategory['partition'] )
            );
            $this->assertEqual( $response['result'], 'ok' );
            $this->assertEqual( count( $response['categories'] ), 1 );

            $response = reset( $response );
            $this->assertEqual( $response[0]['cat_id'], $catId['cat_id'] );
            $this->assertEqual( $response[0]['cat_name'], self::$brandsCategory['cat_name'] );
            $this->assertEqual( $response[0]['cat_icon'], $domain . self::$brandsCategory['cat_icon'] );
            $this->assertFalse( isset( $response['publics_count'] ));
            $this->assertEqual( count( $response[0]['sub'] ), 1 );
            $this->assertEqual( $response[0]['sub'][0]['subcat_id'], $subcat['subcat_id'] );
            $this->assertEqual( $response[0]['sub'][0]['subcat_name'], self::$brandsSubcategory['subcat_name'] );
            $this->assertEqual( $response[0]['sub'][0]['subcat_icon'], $domain . self::$brandsSubcategory['subcat_icon'] );
            $this->assertFalse( isset( $response[0]['sub'][0]['deleted'] ));

        }
    }
