<?php
    namespace Cerceau\I;

    interface IResponse {

        /**
         * @param $name
         * @param null $value
         * @param null $expire
         * @param null $secure
         * @return mixed
         */
        public function cookie( $name, $value = null, $expire = null, $secure = null );

        /**
         * @abstract
         * @param $content
         */
        public function header( $content );
    }
