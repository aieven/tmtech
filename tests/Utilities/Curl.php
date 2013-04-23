<?php
    namespace Cerceau\Test\Utilities;

    class Curl implements \Cerceau\Utilities\I\ICurl {

        /**
         * @var CURL
         */
        protected static $Instance;

        protected function __construct(){}

        /**
         * @static
         * @return CURL
         */
        public static function instance(){
            if(!static::$Instance ){
                static::$Instance = new static();
            }
            return static::$Instance;
        }

        /**
         * @param array $options
         * @return array
         */
        public function authorizeQuery( $options = array() ){
            parse_str( $options[CURLOPT_POSTFIELDS] );
            if( !isset( $code ) )
                return false;
            $id = preg_replace("/\D/Usi", "", md5( $code ) );
            return array(
                'access_token' => md5( $code ),
                'user' => array(
                    'id' => $id,
                    'full_name' => 'user_full_name_'. $id ,
                    'user_name' => 'user_name_'. $id,
                    'profile_picture' => 'http://avatars.com/'. $id .'/'. md5( $code ) .'.jpg',
                ),
            );
        }

        /**
         * @param string $apiLocation
         * @param string $uri
         * @param array $get
         * @param array $post
         * @return array
         */
        public function apiQuery( $apiLocation , $uri, array $get = array(), array $post = array() ){
            $url = $apiLocation .'/'. $uri .'?'. http_build_query( $get ) ;
            $fileName = md5( $url );
            if( !file_exists( TESTS_DIR . 'api/' . $fileName ) ){
                \Cerceau\Utilities\Debug::instance()->dump( 'no such file for request:
                ----  url: https://'. $url . '
                ---- file: '. $fileName );
                $request = array(
                    'apiLocation' => $apiLocation,
                    'uri' => $uri,
                    'get' => $get,
                    'post' => $post,
                );
                \Cerceau\System\Registry::instance()->Logger()->log( 'no-such-files' , $fileName . ': '. json_encode( $request ) , false );
                return false;
            }
            elseif(!$response = file_get_contents( TESTS_DIR . 'api/' . $fileName )){
                \Cerceau\System\Registry::instance()->Logger()->log( 'curl', 'error read file: '. $fileName );
                return false;
            }
            list( $header, $response ) = explode( "\r\n\r\n", $response );

            return array(
                'headers' => $header,
                'body' => json_decode( $response, true )
            );
        }

        public function gcmRequest( $apiSendAddress , $headers , $post ){
            $results = array();
            $post = json_decode( $post , true );
            foreach( $post['registration_ids'] as $regId ){
                $results[] = (object)array(
                    'registration_id' =>  $regId,
                    'message_id' => md5( $regId ),
                );
            }
            \Cerceau\System\Registry::instance()->Logger()->log( 'successfully-notification' , json_encode( $results ) , false );

            return array(
                'info' => array( 'http_code' => 200, ),
                'body' => (object)array(
                    'multicast_id' => 123,
                    'success' => 1,
                    'failure' => 0,
                    'canonical_ids' => 1,
                    'results' => (object)$results,
                ),
            );
        }
    }