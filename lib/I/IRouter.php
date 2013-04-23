<?php
	namespace Cerceau\I;

	interface IRouter {

        /**
         * @abstract
         * @return IController
         */
        public function getController();


        /**
         * @abstract
         * @return string
         */
        public function getUrl();
    }
	