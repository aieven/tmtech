<?php
	namespace Cerceau\Api;

    require_once EXTERNAL_DIR .'recaptcha/recaptchalib.php';

	class Recaptcha {

        public static function html(){
            return
                '<script type="text/javascript"> var RecaptchaOptions = { theme : "'. RECAPTCHA_THEME .'" }; </script>'.
                recaptcha_get_html( RECAPTCHA_PUBLIC_KEY );
        }

        public static function isValid( array $post ){
            if(!isset( $post['recaptcha_challenge_field'], $post['recaptcha_response_field'] ))
                return false;

            $Response = recaptcha_check_answer(
                RECAPTCHA_PRIVATE_KEY,
                $_SERVER['REMOTE_ADDR'],
                $post['recaptcha_challenge_field'],
                $post['recaptcha_response_field']
            );

            return $Response->is_valid;
        }
    }
	