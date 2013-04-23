<?php
    namespace Cerceau\Script;

    abstract class Base {
        abstract public function run();

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
    }
