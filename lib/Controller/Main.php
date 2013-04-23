<?php
    namespace Cerceau\Controller;

    class Main extends \Cerceau\Controller\Base {
        protected static $routes = array(
            'get' => array( // method
                'main' => array(
                    'main',
                    '',
                ),
                'main2' => array(
                    'main',
                    'banner<\w+>/',
                    'params' => array(
                        'banner'
                    ),
                ),
                'admin' => array(
                    'admin',
                    'admin/',
                ),
                'logout' => array(
                    'logout',
                    'logout',
                    'view' => 'Redirect',
                ),
            ),
            'post' => array(
                'auth' => array(
                    'auth',
                    'auth',
                    'view' => 'Json',
                ),
            ),
        );

        protected function pageMain(){
            // instagram auth
            $response = \Cerceau\System\Registry::instance()->Request()->get();
            if(!empty( $response['error'] )){
                \Cerceau\System\Registry::instance()->Logger()->log(
                    'instagram-auth',
                    '['. $_SERVER['REMOTE_ADDR'] .'] '. $response['error'] .
                    ', reason: '. $response['error_reason'] .
                    ', description: '. $response['error_description']
                );
                $this->unauthorizedPath = \Cerceau\System\Registry::instance()->Url()->page( 'user\\Auth', 'error' );
                throw new \Cerceau\Exception\Page();
            }
            if( isset( $response['state'], $response['code'] )){
                $this->unauthorizedPath =
                    \Cerceau\System\Registry::instance()->Url()->page( 'user\\Auth', 'response' )
                    .'?state='. $response['state'] .'&code='. $response['code'];
                throw new \Cerceau\Exception\Page();
            }
            // main page
            $browser = $_SERVER['HTTP_USER_AGENT'];
            if( strpos( $browser, 'iPhone' ) || strpos( $browser, 'Android' ))
                $this->View->template('main_mobile');
            else
                $this->View->template('main_pc');
            return true;
        }

        protected function pageAdmin(){
            if( $this->Auth && $this->Auth->isReal()){
                \Cerceau\Config\Special::instance()->set( 'locale', 'ru_RU' );
                $this->View->page( 'auth', $this->Auth );
                $this->View->set( 'privileges', $this->Auth['privileges']->export());
                $this->View->layout('admin');
                $this->View->template('admin/main');
            }
            else {
                $this->View->template('admin/auth');
                $this->View->globals( 'controller', 'Admin.Auth' );
            }
            return true;
        }

        protected function pageAuth(){
            \Cerceau\Config\Special::instance()->set( 'locale', 'ru_RU' );

            $request = \Cerceau\System\Registry::instance()->Request()->post();
            if(!isset( $request['email'], $request['password'] ))
                throw new \Cerceau\Exception\Request();

            try {
                $Auth = new \Cerceau\Data\User\Auth();
                if(!$Auth->load( $request ))
                    throw new \Cerceau\Exception\Authorize( 'Wrong auth data in '. __CLASS__ );

                $this->Auth = $Auth;
            }
            catch( \Cerceau\Exception\Authorize $E ){
                throw new \Cerceau\Exception\Client( 'auth.forms.wrong_data' );
            }
            catch( \Exception $E ){
                throw $E;
            }
            return true;
        }

        protected function pageLogout(){
            $Registry = \Cerceau\System\Registry::instance();
            $this->Auth = null;
            $this->View->set( 'location', $Registry->DomainConfig()->main() . $Registry->Url()->page( 'main', 'admin' ));
            return true;
        }
    }
