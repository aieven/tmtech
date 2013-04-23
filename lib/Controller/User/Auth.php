<?php
    namespace Cerceau\Controller\User;

    use \Cerceau\Exception\Factory as ExceptionFactory;

    class Auth extends \Cerceau\Controller\Base {

        /**
         * redirect from
         *
         * https://api.instagram.com/oauth/authorize/?client_id=CLIENT-ID&redirect_uri=REDIRECT-URI&response_type=code
         *
         * have to check state
         */

        protected static $routes = array(
            'get' => array( // method
                'login' => array(
                    'login',
                    'api/v1/auth/instagram/',
                    'view' => 'Redirect',
                ),
                'response' => array(
                    'response',
                    'api/auth/instagram/',
                    'view' => 'Redirect',
                ),
                'success' => array(
                    'success',
                    'api/auth/success/',
                    'view' => 'Json',
                ),
                'error' => array(
                    'error',
                    'api/auth/error/',
                    'view' => 'Redirect',
                ),
            ),
        );

        protected function authorization(){
            return true;
        }

        protected function pageLogin(){
            $this->Session['state'] = sha1(uniqid());

            $this->View->set( 'location', \Cerceau\Data\User\InstagramUser::DIALOG_LOCATION );
            $this->View->set( 'client_id', \Cerceau\Config\Constants::INSTAGRAM_CLIENT_ID );
            $this->View->set( 'redirect_uri', \Cerceau\Config\Constants::INSTAGRAM_REDIRECT_URL );
            $this->View->set( 'response_type', 'code' );
            $this->View->set( 'scope', \Cerceau\Config\Constants::INSTAGRAM_SCOPE );
            $this->View->set( 'state', $this->Session['state'] );
            return true;
        }

        protected function pageResponse(){
            $Registry = \Cerceau\System\Registry::instance();
            $response = $Registry->Request()->get();

            try {
                if(!empty( $response['error'] ))
                    throw new \Cerceau\Exception\Page( 'Instagram error: '. $response['error'] .', reason: '. $response['error_reason'] );

                if(!isset( $this->Session['state'] ))
                    throw new \Exception( 'Instagram authorization failed. State is missing.' );

                if(
                    !isset( $response['state'], $response['code'] ) ||
                    $response['state'] !== $this->Session['state']
                )
                    throw new \Exception( 'Instagram authorization failed. State is wrong.' );

                $Auth = new \Cerceau\Data\User\InstagramUser();
                $Auth->authorize( $response['code'] );
            }
            catch( \Cerceau\Exception\Page $E ){
                $Registry->Logger()->log( 'instagram-auth-error', '['. $_SERVER['REMOTE_ADDR'] .'] '. $E->getMessage());
                $this->View->set( 'error', 'Please authorize the application to sign in.' );
                $this->View->set( 'location', $Registry->DomainConfig()->main( 'https' ) . $Registry->Url()->page( 'user\\Auth', 'error' ) .'?');
                return true;
            }
            catch( \Exception $E ){
                $Registry->Logger()->log( 'instagram-auth-error', '['. $_SERVER['REMOTE_ADDR'] .'] '. $E->getMessage());
                $this->View->set( 'error', 'Instagram has produced an error. Try to sign in again.' );
                $this->View->set( 'location', $Registry->DomainConfig()->main( 'https' ) . $Registry->Url()->page( 'user\\Auth', 'error' ) .'?');
                return true;
            }
            $this->Session['instagram'] = $Auth->export();
            $Response = $Registry->Response();
            $Response->cookie( 'instagram_id', $Auth['instagram_id'], null, true );
            $Response->cookie( 'user_name', $Auth['username'], null, true );
            $Response->cookie( 'full_name', $Auth['full_name'], null, true );
            $Response->cookie( 'profile_picture', $Auth['profile_picture'], null, true );
            $Response->cookie( 'access_token', $Auth['instagram_token'], null, true );
            $this->View->set( 'location', $Registry->DomainConfig()->main( 'https' ) . $Registry->Url()->page( 'user\\Auth', 'success' ) .'?');
            return true;
        }

        protected function pageSuccess(){
            if( $this->Session['instagram'] )
                $this->View->set( $this->Session['instagram'] );
            return true;
        }

        protected function pageError(){
            $Registry = \Cerceau\System\Registry::instance();
            $this->View->set( 'location', $Registry->DomainConfig()->main( 'https' ) . $Registry->Url()->page( 'user\\Auth', 'login' ) .'?');
            return true;
        }
    }
