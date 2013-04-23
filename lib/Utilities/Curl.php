<?php
    namespace Cerceau\Utilities;

    class Curl implements I\ICurl {

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
            $ch = curl_init();
            curl_setopt_array( $ch, $options );
            if(!$response = curl_exec( $ch )){
                \Cerceau\System\Registry::instance()->Logger()->log( 'curl', curl_error( $ch ));
                \Cerceau\Exception\Factory::throwError( \Cerceau\Exception\Factory::INSTAGRAM_AUTH_ERROR, true );
            }
            curl_close($ch);

            return json_decode( $response, true );
        }

        /**
         * @param string $apiLocation
         * @param string $uri
         * @param array $get
         * @param array $post
         * @return array
         */
        public function apiQuery( $apiLocation, $uri, array $get = array(), array $post = array() ){
            $options = array(
                CURLOPT_URL => 'https://'. $apiLocation .'/'. $uri .'?'. http_build_query( $get ),
                CURLOPT_HEADER => 1,
                CURLOPT_FRESH_CONNECT => 1,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_FORBID_REUSE => 1,
                CURLOPT_TIMEOUT => 20,
            );
            if( $post ){
                $options += array(
                    CURLOPT_POST => 1,
                    CURLOPT_POSTFIELDS => http_build_query( $post )
                );
            }
            $ch = curl_init();
            curl_setopt_array( $ch, $options );
            if(!$response = curl_exec( $ch )){
                \Cerceau\System\Registry::instance()->Logger()->log( 'curl', curl_error( $ch ));
                \Cerceau\Exception\Factory::throwError( \Cerceau\Exception\Factory::INSTAGRAM_AUTH_ERROR, true );
            }
            curl_close($ch);

            list( $header, $response ) = explode( "\r\n\r\n", $response );

            return array(
                'headers' => $header,
                'body' => json_decode( $response, true )
            );
        }

        public function gcmRequest( $apiSendAddress , $headers , $post ){
            $options = array(
                CURLOPT_URL => $apiSendAddress,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_FOLLOWLOCATION => 0,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_HEADER => 1,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_FRESH_CONNECT => 1,
                CURLOPT_FORBID_REUSE => 1,
                CURLOPT_TIMEOUT => 20,
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => $post,
            );
            $ch = curl_init();
            curl_setopt_array( $ch, $options );
            $response = curl_exec( $ch );
            $info = curl_getinfo( $ch );
            if( !$response || !$info ){
                \Cerceau\System\Registry::instance()->Logger()->log( 'curl', curl_error( $ch ));
                \Cerceau\Exception\Factory::throwError( \Cerceau\Exception\Factory::INSTAGRAM_AUTH_ERROR, true );
            }
            curl_close( $ch );

            $response = explode( "\r\n\r\n", $response );

            \Cerceau\System\Registry::instance()->Logger()->log( 'request' , json_encode( array( $apiSendAddress, $headers, $post ) ) . ' - '. json_encode( array( 'info' => $info, 'body' => json_decode( $response[1] ) ) ) , false  );

            return array(
                'info' => $info,
                'body' => json_decode( $response[1] ),
            );
        }
    }