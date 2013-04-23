<?php
    namespace Cerceau\Data\Admin\Gallery;

    class Gallery extends \Cerceau\Data\Base\DbRow {

        const
            WIDTH_SIZE = 620,
            HEIGHT_SIZE = 132,
            IMG_DIR = 'img/gallery/'
        ;

        protected static $modelName = 'PostgreSQL\\Base';
        protected static $db = 'main';
        protected static $table = 'gal_categories';

        protected function initialize(){
            self::$fieldsOptions = array(
                'gallery_id' => array(
                    'Int',
                    'const',
                    'load',
                    'autoIncrement',
                ),
                'name' => array(
                    'Scalar',
                    'validation' => array(
                        'notEmpty',
                    ),
                ),
                'order_id' => array(
                    'Int',
                ),
                'icon' => array(
                    'Scalar',
                    'validation' => array(
                        'notEmpty',
                    ),
                ),
                'published' => array(
                    'Int',
                    'default' => '0',
                ),
                'deleted' => array(
                    'Int',
                    'default' => '0',
                ),
            );
            parent::initialize();
        }
    }