<?php
    namespace Cerceau\IO;

    class TestResponse implements \Cerceau\I\IResponse {

        /**
         * @param string $name
         * @param null $value
         * @param null $expire
         * @param null $secure
         */
        public function cookie( $name, $value = null, $expire = null, $secure = null ){}

        /**
         * @param $content
         */
        public function header( $content ){}
    }
