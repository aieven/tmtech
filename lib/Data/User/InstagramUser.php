<?php
    namespace Cerceau\Data\User;

	class InstagramUser extends \Cerceau\Data\Base\DbRow {

        protected static $modelName = 'PostgreSQL\\Base';
        protected static $db = 'main';
        protected static $table = 'users';

        protected static $fieldsOptions = array(
            'instagram_id' => array(
                'Int',
                'load',
                'const',
            ),
            'username' => array(
                'Scalar',
                'foreign',
            ),
            'profile_picture' => array(
                'Scalar',
                'foreign',
            ),
            'full_name' => array(
                'Scalar',
                'foreign',
            ),
            'instagram_token' => array(
                'Scalar',
                'load',
            ),
            'request_available' => array(
                'DateTime',
            ),
        );

        const
            DIALOG_LOCATION = 'https://api.instagram.com/oauth/authorize/?',
            API_LOCATION = 'api.instagram.com'
        ;

        public function authorize( $code ){
            $options = array(
                CURLOPT_URL => 'https://'. self::API_LOCATION .'/oauth/access_token',
                CURLOPT_HEADER => 0,
                CURLOPT_POST => 1,
                CURLOPT_FRESH_CONNECT => 1,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_FORBID_REUSE => 1,
                CURLOPT_TIMEOUT => 4,
                CURLOPT_POSTFIELDS => http_build_query( array(
                    'client_id' => \Cerceau\Config\Constants::INSTAGRAM_CLIENT_ID,
                    'client_secret' => \Cerceau\Config\Constants::INSTAGRAM_CLIENT_SECRET,
                    'redirect_uri' => \Cerceau\Config\Constants::INSTAGRAM_REDIRECT_URL,
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                ))
            );
            $ch = curl_init();
            curl_setopt_array( $ch, $options );
            if(!$response = curl_exec( $ch )){
                \Cerceau\System\Registry::instance()->Logger()->log( 'curl', curl_error( $ch ));
                throw new \Exception( 'Instagram authorization failed. Cannot get access token.' );
            }
            curl_close($ch);

            $response = json_decode( $response, true );
            if(!$response || empty( $response['access_token'] ) || empty( $response['user'] ))
                throw new \Exception( 'Instagram authorization failed. Cannot get access token.' );

            $this->fetch( array(
                'instagram_id' => $response['user']['id'],
                'username' => $response['user']['username'],
                'profile_picture' => $response['user']['profile_picture'],
                'full_name' => $response['user']['full_name'],
                'instagram_token' => $response['access_token'],
            ));
        }

        public function api( $uri, array $get = array(), array $post = array()){
            if(!$this['instagram_token'] )
                throw new \Cerceau\Exception\Authorize();

            $get['access_token'] = $this['instagram_token'];
            $options = array(
                CURLOPT_URL => 'https://'. self::API_LOCATION .'/'. $uri .'?'. http_build_query( $get ),
                CURLOPT_HEADER => 1,
                CURLOPT_FRESH_CONNECT => 1,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_FORBID_REUSE => 1,
                CURLOPT_TIMEOUT => 4,
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

            if( preg_match( '#X\-Ratelimit\-Remaining\:\s+(\d+)#', $header, $match ))
                $this['request_available'] = $match[1];

            return json_decode( $response, true );
        }
    }
