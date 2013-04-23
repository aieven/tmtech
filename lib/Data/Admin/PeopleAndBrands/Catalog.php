<?php
    namespace Cerceau\Data\Admin\PeopleAndBrands;

    class Catalog extends \Cerceau\Data\Base\Row {
        const
            ORDER_BY_FOLLOWERS = 0,
            ORDER_BY_LIKES = 1,
            ORDER_BY_COMMENTS = 2
        ;

        protected static $methods = array(
            self::ORDER_BY_FOLLOWERS,
            self::ORDER_BY_LIKES,
            self::ORDER_BY_COMMENTS,
        );

        /**
         * @var \Cerceau\Model\PostgreSQL\Admin\PeopleAndBrands\Catalog
         */
        protected $Model;

        public function initialize(){
            self::$fieldsOptions = array(
                'partition' => array(
                    'Scalar',
                ),
                'instagram_id' => array(
                    'Int',
                ),
                'subcat_id' => array(
                    'Int',
                ),
                'cat_id' => array(
                    'Int',
                ),
                'max_by' => array(
                    'Int'
                ),
                'use_method' => array(
                    'Enum',
                    'types' => self::$methods,
                ),
                'order_by' => array(
                    'Enum',
                    'types' => array(
                        'followers' => self::ORDER_BY_FOLLOWERS,
                        'likes' => self::ORDER_BY_LIKES,
                        'comments' => self::ORDER_BY_COMMENTS,
                    ),
                ),
                'limit' => array(
                    'Int',
                    'default' => 20,
                ),
                'full_name' => array(
                    'Scalar'
                ),
                'is_api' => array(
                    'Boolean'
                ),
            );
            $this->Model = new \Cerceau\Model\PostgreSQL\Admin\PeopleAndBrands\Catalog( 'main' );
        }

        protected function preFetch( $a ){
            if( isset( $a['use_method'] )){
                $a['order_by'] = $this->fields['order_by']->searchValue( $a['use_method'] );
            }
            return $a;
        }

        public static function methodName( $method ){
            if(!in_array( $method, self::$methods ))
                return '';
            $methodsNames = \Cerceau\System\Registry::instance()->I18n()->pick( 'admin', 'charts', 'use_method' );
            return $methodsNames[$method];
        }

        public function checkMethod( $replace = false ){
            if( is_null( $this['use_method'] ) || $replace ){
                $ChartsKey = new \Cerceau\Model\Redis\Key( 'charts' );
                $this['use_method'] = intval( $ChartsKey->get( 'method' ));
                $this['order_by'] = $this->fields['order_by']->searchValue( $this['use_method'] );
                return true;
            }
            return false;
        }

        public function saveMethod(){
            if( is_null( $this['use_method'] ))
                return false;

            $ChartsKey = new \Cerceau\Model\Redis\Key( 'charts' );
            return $ChartsKey->set( 'method', $this['use_method'] );
        }

        public function selectCategories(){
            $categories = $this->Model->selectCategories( $this->export());
            $subcategories = $this->Model->selectSubcategories( $this->export());
            foreach( $categories as &$category ){
                $catPublicsCount = $this->Model->selectCatPublicsCount( array(
                     'cat_id' => $category['cat_id'],
                     'partition' => $this['partition'],
                ));
                $category['publics_count'] = $catPublicsCount[0];
                $category['sub'] = array();
                foreach( $subcategories as &$subcategory ){
                    if( $subcategory['cat_id'] == $category['cat_id'] ){
                        $publicsCount = $this->Model->selectSubcatPublicsCount( array(
                            'subcat_id' => $subcategory['subcat_id'],
                            'partition' => $this['partition'],
                        ));
                        $subcategory['publics_count'] = $publicsCount[0];
                        $category['sub'][] = $subcategory;
                    }
                }
            }
            return $categories;
        }

        public function selectPublics(){
            $publics = $this->Model->selectPublics( $this->export());
            if( $publics === false )
                \Cerceau\Exception\Factory::throwError( \Cerceau\Exception\Factory::DATABASE_ERROR );
            return $publics;
        }

        public function selectCharts(){
            return $this->Model->selectCharts( $this->export());
        }

        public function selectCategoriesApi(){
            $domain = \Cerceau\System\Registry::instance()->DomainConfig()->sub( 'img' );
            $categories = $this->selectCategories();
            foreach( $categories as &$category){
                $category['cat_icon'] = $domain . $category['cat_icon'];
                unset( $category['publics_count'] );
                foreach( $category['sub'] as &$subcategory ){
                    $subcategory['subcat_icon'] = $domain . $subcategory['subcat_icon'];
                    unset( $subcategory['deleted'] );
                }
            }
            return $categories;
        }

        public function selectPublicsApi(){
            $publics = $this->selectPublics();
            foreach( $publics as &$public ){
                if( isset( $public['cat_id'] )){
                    unset(
                        $public['cat_id'],
                        $public['subcat_id'],
                        $public['photos']
                    );
                }
            }
            return $publics;
        }

        public function searchPublic(){
            $query = $this['full_name'];
            $this['full_name'] = '%' . trim( $this['full_name'] ) . '%';
            $publics = $this->selectPublicsApi();
            foreach( $publics as $key => $public ){
                if(!preg_match( '/\b' . preg_quote( $query, '/' ) . '/i', $public['full_name'] ) )
                    unset( $publics[$key] );
            }
            return array_values( $publics );
        }

    }
