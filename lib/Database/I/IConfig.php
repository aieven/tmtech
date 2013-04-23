<?php
    namespace Cerceau\Database\I;

	interface IConfig {
        /**
         * @abstract
         * @param string $name
         * @return string
         */
        public function get( $name );

        /**
         * @abstract
         * @return IDriver
         */
        public function getDriver();

        /**
         * @abstract
         * @return ISQLTemplator
         */
        public function getTemplator();

        /**
         * @abstract
         * @return ITablesConfig
         */
        public function getTablesConfig();
	}
	