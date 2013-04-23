<?php
    namespace Cerceau\Controller\Api;

    class Version extends Base {
        protected static $routes = array(
            'get' => array( // method
                'apkVersion' => array(
                    'apkVersion',
                    'api/version/apk/',
                ),
                'view' => 'Json',
            ),
        );

        protected function pageApkVersion(){
            try {
                $this->View->set( 'version', intval( \Cerceau\NoSQL\Redis::instance()->get(1)->get( 'apk_version' )));
            }
            catch( \Exception $E ){
                \Cerceau\Exception\Factory::throwError( \Cerceau\Exception\Factory::REDIS_ERROR );
                \Cerceau\System\Registry::instance()->Logger()->logException( $E );
            }
            return true;
        }
    }
