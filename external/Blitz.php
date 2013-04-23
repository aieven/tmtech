<?php
    # dummy

    class Blitz {
        /**
         * @abstract
         * @param $context_path
         * @param array|null $parameters
         * @return bool
         */
        public function block( $context_path, array $parameters = null ){}

        /**
         * @abstract
         * @param string $context_path
         * @param bool $warn_notfound
         * @return bool
         */
        public function clean( $context_path = '/', $warn_notfound = true ){}

        /**
         * @abstract
         * @param $context_path
         * @return bool
         */
        public function context( $context_path ){}

        /**
         * @abstract
         * @return bool
         */
        public function dumpIterations(){}

        /**
         * @abstract
         * @return bool
         */
        public function dumpStruct(){}

        /**
         * @abstract
         * @param $name
         * @param array $parameters
         * @return string
         */
        public function fetch( $name, array $parameters = null ){}

        /**
         * @abstract
         * @param $context_path
         * @return bool
         */
        public function hasContext( $context_path ){}

        /**
         * @abstract
         * @param $context_path
         * @return bool
         */
        public function iterate( $context_path = null ){}

        /**
         * @abstract
         * @param $tpl
         * @return bool
         */
        public function load( $tpl ){}

        /**
         * @abstract
         * @param $global_vars
         * @return string
         */
        public function parse( array $global_vars = null ){}

        /**
         * @abstract
         * @param $parameters
         * @return bool
         */
        public function set( array $parameters ){}

        /**
         * @abstract
         * @param $parameters
         * @return bool
         */
        public function setGlobal( array $parameters ){}

        /**
         * @abstract
         * @param $template_name
         * @param array $global_vars
         */
        public function __include( $template_name, array $global_vars ){}
    }