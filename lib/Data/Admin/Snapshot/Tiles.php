<?php

    namespace Cerceau\Data\Admin\Snapshot;

    class Tiles extends \Cerceau\Data\Base\Row {

        protected function initialize(){
            self::$fieldsOptions = array(
                'tile_type' => array(
                    'Enum',
                    'types' => \Cerceau\Data\Admin\Snapshot\TileTypes::types(),
                    'const',
                ),
                'link' => array(
                    'Scalar'
                ),
                'img' => array(
                    'Scalar'
                )
            );
            parent::initialize();
        }
    }
