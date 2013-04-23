<?php
    namespace Cerceau\Test;

    abstract class Base extends \UnitTestCase {

        /**
         * @param $name
         * @return \Cerceau\Database\I\IDatabase
         */
        final protected function db( $name ){
            return \Cerceau\System\Registry::instance()->DatabaseConnection()->get( $name );
        }

        /**
         * @param $name
         * @param $spotId
         * @return \Cerceau\Database\I\IDatabase
         */
        final protected function spotDb( $name, $spotId ){
            return \Cerceau\System\Registry::instance()->DatabaseSpotConnection()->get( $name, $spotId );
        }

        /**
         * @param string $logFile
         * @param string $string
         * @param bool $isContain
         */
        protected function assertLog( $logFile, $string, $isContain = true ){
            $logPath = LOGS_DIR .'test/'. $logFile .'.log';
            $log = false;
            if( file_exists( $logPath ))
                $log = file_get_contents( $logPath );
            $this->assertIdentical( !!$isContain, $log && strpos( $log, $string ) !== false, 'Log file "'. $logFile .'" must '. ( $isContain ? ''  : 'not ' ) .'contain string "'. $string .'"' );
        }

        protected function clearLog( $logFile ){
            $logPath = LOGS_DIR .'test/'. $logFile .'.log';
            if( file_exists( $logPath ))
                unlink( $logPath );
        }

        protected function getPage( $method, $url, $ajax = false ){
            $Router = new \Cerceau\System\RouterTest( $method, $url, $ajax );
            return $Router->getController()->run();
        }

        protected function runScript( $script ){
            $scriptClass = '\\Cerceau\\Script\\'. $script .'Script';
            if(!class_exists( $scriptClass, true ))
                throw new \ErrorException( 'Script not exists "'. $script .'"' );

            /**
             * @var \Cerceau\Script\Base $Script
             */
            $Script = new $scriptClass();
            $Script->run();
        }

    }
