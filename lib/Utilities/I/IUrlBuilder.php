<?php
    namespace Cerceau\Utilities\I;

    interface IUrlBuilder {
        /**
         * @param string $controllerName
         * @param string $pageName
         * @param array $arguments
         * @return string
         */
        public function page( $controllerName, $pageName, array $arguments = array());

        /**
         * @param string $controllerName
         * @param string $pageName
         * @param array $arguments
         * @return string
         */
        public function form( $controllerName, $pageName, array $arguments = array());

        /**
         * @param string $method
         * @param string $controllerName
         * @param string $pageName
         * @param array $arguments
         * @return string
         * @throws \UnexpectedValueException
         */
        public function route( $method, $controllerName, $pageName, array $arguments = array());

        /**
         * @abstract
         * @param string $localPath
         * @param string $theme
         * @return string
         */
        public function image( $localPath, $theme = 'default' );

        /**
         * @return string
         */
        public function sitename();
    }