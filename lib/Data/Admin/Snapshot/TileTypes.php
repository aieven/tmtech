<?php

    namespace Cerceau\Data\Admin\Snapshot;

    class TileTypes {
        const
            BIG = 1,
            SMALL = 2
        ;

        private static $types = array(
            self::BIG => 1,
            self::SMALL => 2,
        );

        public static function types(){
            return self::$types;
        }
    }
