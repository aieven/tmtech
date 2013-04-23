<?php
    namespace Cerceau\Controller\Api;

    class PeopleAndBrands extends Base {
        protected static $routes = array(
            'get' => array( // method
                'categories' => array(
                    'categories',
                    'api/v1/<people|brands>/categories/',
                    'params' => array(
                        'partition',
                    ),
                ),
                'categoryPublics' => array(
                    'publics',
                    'api/v1/<people|brands>/categories/<\d+>/',
                    'params' => array(
                        'partition',
                        'cat_id',
                    ),
                ),
                'subcategoryPublics' => array(
                    'publics',
                    'api/v1/<people|brands>/categories/<\d+>/<\d+>/',
                    'params' => array(
                        'partition',
                        'cat_id',
                        'subcat_id',
                    ),
                ),
                'charts' => array(
                    'charts',
                    'api/v1/people/charts/',
                ),
                'search' => array(
                    'search',
                    'api/v1/<people|brands>/search/',
                    'params' => array(
                        'partition',
                    ),
                ),
            ),
        );

        protected function pageCategories(){
            $People = new \Cerceau\Data\Admin\PeopleAndBrands\Catalog();
            $People->fetch( $this->queryParam());
            $categories = $People->selectCategoriesApi();
            if( $categories === false )
                \Cerceau\Exception\Factory::throwError( \Cerceau\Exception\Factory::DATABASE_ERROR );

            $this->View->set( 'categories', $categories );
            return true;
        }

        protected function pagePublics(){
            // sort by name
            $Publics = new \Cerceau\Data\Admin\PeopleAndBrands\Catalog();
            $Publics->fetch( $this->queryParam());
            $Publics['is_api'] = true;
            $publics = $Publics->selectPublicsApi();
            if( $publics === false )
                \Cerceau\Exception\Factory::throwError( \Cerceau\Exception\Factory::DATABASE_ERROR );

            $Publics->checkMethod(); // no sort, just send

            $this->View->set( 'publics', $publics );
            if( $Publics['partition'] === 'people' )
                $this->View->set( 'use_method', $Publics['use_method'] );
            return true;
        }

        protected function pageCharts(){
            $Publics = new \Cerceau\Data\Admin\PeopleAndBrands\Catalog();
            $Publics->fetch( \Cerceau\System\Registry::instance()->Request()->get());
            $Publics['is_api'] = true;
            $Publics->checkMethod(); // sort by use_method
            $publics = $Publics->selectCharts();
            if( $publics === false )
                \Cerceau\Exception\Factory::throwError( \Cerceau\Exception\Factory::DATABASE_ERROR );

            $this->View->set( 'publics', $publics );
            if( $Publics['partition'] === 'people' )
                $this->View->set( 'use_method', $Publics['use_method'] );
            return true;
        }

        protected function pageSearch(){
            // sort by name
            $Publics = new \Cerceau\Data\Admin\PeopleAndBrands\Catalog();
            $Publics->fetch( $this->queryParam() + \Cerceau\System\Registry::instance()->Request()->get());
            $Publics['is_api'] = true;
            $publics = $Publics->searchPublic();
            if( $publics === false )
                \Cerceau\Exception\Factory::throwError( \Cerceau\Exception\Factory::DATABASE_ERROR );

            $Publics->checkMethod(); // no sort, just send

            $this->View->set( 'publics', $publics );
            if( $Publics['partition'] === 'people' )
                $this->View->set( 'use_method', $Publics['use_method'] );
            return true;
        }

    }