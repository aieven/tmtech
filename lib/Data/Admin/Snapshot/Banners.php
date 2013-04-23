<?php
    namespace Cerceau\Data\Admin\Snapshot;

    class Banners extends \Cerceau\Data\Base\Row {

        protected function initialize(){
            self::$fieldsOptions = array(
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