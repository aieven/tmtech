<?php
    namespace Cerceau\Data\Admin\Snapshot;

    class BannersImageUploader extends \Cerceau\Data\Image\ImageUploader {

        protected static $iconDir = 'img/snapshot/banners/';
        protected static $widthSize = 620;
        protected static $heightSize = 329;

        protected function initialize(){
            self::$fieldsOptions = array(
                'name' => array(
                    'Int'
                ),
                'type' => array(
                    'Enum',
                    'types' => \Cerceau\Data\Image\Types::mimetypes(),
                    'const',
                ),
                'link' => array(
                    'Scalar'
                ),
                'image_path' => array(
                    'Scalar',
                ),
            );
            parent::initialize();
        }
    }