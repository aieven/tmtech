<?php
    namespace Cerceau\Data\Admin\PeopleAndBrands;

    class IconsImageUploader extends \Cerceau\Data\Image\ImageUploader {

        protected static $iconDir = 'img/subcategories/icons/';
        protected static $widthSize = 144;
        protected static $heightSize = 144;

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
                    'Scalar',
                ),
            );
            parent::initialize();
        }
    }