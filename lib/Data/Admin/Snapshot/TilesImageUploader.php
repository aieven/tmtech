<?php
    namespace Cerceau\Data\Admin\Snapshot;

    class TilesImageUploader extends \Cerceau\Data\Image\ImageUploader {

        protected static $iconDir = 'img/snapshot/tiles/';
        protected static $heightSize = 192;

        protected function initialize(){
            self::$fieldsOptions = array(
                'type' => array(
                    'Enum',
                    'types' => \Cerceau\Data\Image\Types::mimetypes(),
                    'const',
                ),
                'name' => array(
                    'Int',
                ),
                'tile_type' => array(
                    'Enum',
                    'types' => \Cerceau\Data\Admin\Snapshot\TileTypes::types(),
                    'const',
                ),
                'link' => array(
                    'Scalar'
                ),
                'image_path' => array(
                    'Scalar'
                ),
            );
            parent::initialize();
        }
    }