<?php
	namespace Cerceau\I;

    /**
     * Dummy, implements by RegistryConfig
     * Defined methods could be call
     */

	interface IRegistry {

        /**
         * @abstract
         * @return IRouter
         */
        public function Router();

        /**
         * @abstract
         * @return \Cerceau\I\IRequest
         */
        public function Request();

        /**
         * @abstract
         * @return \Cerceau\I\IResponse
         */
        public function Response();

        /**
         * @abstract
         * @return ISession
         */
        public function Session();

        /**
         * @abstract
         * @return \Cerceau\Utilities\I\IDebug
         */
        public function Debug();

        /**
         * @abstract
         * @return \Cerceau\Utilities\I\ILogger
         */
        public function Logger();

        /**
         * @abstract
         * @return \Cerceau\Utilities\I\IUrlBuilder
         */
        public function Url();

        /**
         * @abstract
         * @return \Cerceau\Utilities\I\IDate
         */
        public function Date();

        /**
         * @abstract
         * @return \Cerceau\Utilities\I\ITimer
         */
        public function Timer();

        /**
         * @abstract
         * @return \Cerceau\Utilities\I\ISpecialConfig
         */
        public function SpecialConfig();

        /**
         * @abstract
         * @return \Cerceau\Utilities\I\IDomainConfig
         */
        public function DomainConfig();

        /**
         * @return \Cerceau\Utilities\I\II18n
         */
        public function I18n();

        /**
         * @return \Cerceau\NoSQL\Redis
         */
        public function Redis();

        /**
         * @return \Cerceau\Database\I\IConnection
         */
        public function DatabaseConnection();

        /**
         * @return \Cerceau\Database\I\ISpotConnection
         */
        public function DatabaseSpotConnection();

        /**
         * @return \Cerceau\Utilities\I\ICurl
         */
        public function Curl();
    }
	