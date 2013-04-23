<?php
    namespace Cerceau\Controller\Api;

    class Gallery extends Base {
        protected static $routes = array(
            'get' => array( // method
                'galleries' => array(
                    'galleries',
                    'api/v1/galleries/',
                ),
                'publics' => array(
                    'publics',
                    'api/v1/gallery/<\d+>/publics/',
                    'params' => array(
                        'gallery_id'
                    )
                ),
                'media' => array(
                    'media',
                    'api/v1/galleries/<\d+>/',
                    'params' => array(
                        'gallery_id',
                    ),
                ),
            ),
        );

        protected function pageGalleries(){
            $Categories = new \Cerceau\Data\Admin\Gallery\Catalog();
            $Categories->fetch( array( 'published' => 1 ));
            $categories = $Categories->selectGalleryCategoriesApi();
            if( $categories === false )
                \Cerceau\Exception\Factory::throwError( \Cerceau\Exception\Factory::DATABASE_ERROR );

            $this->View->set( 'galleries', $categories );
            return true;
        }

        protected function pageMedia(){
            $get = \Cerceau\System\Registry::instance()->Request()->get();
            $GalleryCatalog = new \Cerceau\Data\Admin\Gallery\Publics\Catalog();
            $GalleryCatalog->fetch( array( 'api' => 1 ) + $this->queryParam() + $get );
            $data = $GalleryCatalog->selectPublicsMedia();
            if( $data === false )
                \Cerceau\Exception\Factory::throwError( \Cerceau\Exception\Factory::DATABASE_ERROR );

            $this->View->set( 'media', $data['media'] );
            $this->View->set( 'pagination', $data['pagination'] );
            return true;
        }

        protected function pagePublics(){
            $GalleryCatalog = new \Cerceau\Data\Admin\Gallery\Publics\Catalog();
            $GalleryCatalog->fetch( array( 'api' => 1 ) + $this->queryParam());
            $data = $GalleryCatalog->selectPublics();
            if( $data === false )
                \Cerceau\Exception\Factory::throwError( \Cerceau\Exception\Factory::DATABASE_ERROR );

            $this->View->set( 'publics', $data );
            return true;
        }
    }
