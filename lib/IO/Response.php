<?php
    namespace Cerceau\IO;

    class Response implements \Cerceau\I\IResponse {

        /**
         * @param $name
         * @param null $value
         * @param null $expire
         * @param null $secure
         * @return mixed|void
         */
        public function cookie( $name, $value = null, $expire = null, $secure = null ){
            setcookie( $name, $value, $expire, '/', null, $secure );
        }

        /**
         * @param $content
         */
        public function header( $content ){
            header( $content );
        }
    }
