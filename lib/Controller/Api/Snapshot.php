<?php
    namespace Cerceau\Controller\Api;

    class Snapshot extends Base {
        protected static $routes = array(
            'get' => array( // method
                'snapshot' => array(
                    'snapshot',
                    'api/v1/main/',
                ),
            ),
        );

        protected function pageSnapshot(){
            $Snapshot = new \Cerceau\Data\Admin\Snapshot\Catalog();
            $snapshot = $Snapshot->selectPublished();
            if( $snapshot === false )
                \Cerceau\Exception\Factory::throwError( \Cerceau\Exception\Factory::DATABASE_ERROR );

            $this->View->set( 'main', $snapshot );
            return true;
        }
    }
