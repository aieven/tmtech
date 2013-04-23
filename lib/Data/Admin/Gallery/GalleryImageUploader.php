<?php
    namespace Cerceau\Data\Admin\Gallery;

    class GalleryImageUploader extends \Cerceau\Data\Image\ImageUploader {

        protected static $iconDir = 'img/gallery/';
        protected static $widthSize = 612;
        protected static $heightSize = 132;

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
                'image_path' => array(
                    'Scalar'
                ),
            );
            parent::initialize();
        }
    }