<?php
    namespace Cerceau\Config;

    use \Cerceau\Utilities as Utils;

    class Registry extends \Cerceau\System\RegistryConfig {

        public function Router(){
            return \Cerceau\System\Router::instance();
        }

        public function SpecialConfig(){
            return \Cerceau\Config\Special::instance();
        }

        public function DomainConfig(){
            return \Cerceau\Config\Domain::instance();
        }

        public function Redis(){
            return \Cerceau\NoSQL\Redis::instance();
        }

        public function DatabaseConnection(){
            return \Cerceau\Database\Connection::instance();
        }

        public function DatabaseSpotConnection(){
            return \Cerceau\Database\SpotConnection::instance();
        }

        public function Request(){
            return \Cerceau\IO\Request::instance();
        }

        public function Response(){
            return new \Cerceau\IO\Response();
        }

        public function Session(){
            return \Cerceau\System\Session::instance( $this->Request(), $this->Response());
        }

        public function Debug(){
            return Utils\Debug::instance();
        }

        public function Logger(){
            return Utils\Logger::instance( LOGS_DIR . \Cerceau\Autoloader::platfotm());
        }

        public function Url(){
            return Utils\UrlBuilder::instance();
        }

        public function Date(){
            return Utils\Date::instance();
        }

        public function Timer(){
            return Utils\Timer::instance();
        }

        public function I18n(){
            return Utils\I18n::instance( \Cerceau\System\Registry::instance()->SpecialConfig()->get( 'locale' ));
        }

        public function Curl(){
            return Utils\Curl::instance();
        }
    }
