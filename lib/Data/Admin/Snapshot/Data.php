<?php

    namespace Cerceau\Data\Admin\Snapshot;

    class Data extends \Cerceau\Data\Base\Row {
        protected static $fieldsOptions = array(
            'banners' => array(
                'RowArray',
                'class' => 'Admin\\Snapshot\\Banners',
            ),
            'charts' => array(
                'FieldArray',
                'fieldsOptions' => array(
                    'Int',
                ),
            ),
            'tiles' => array(
                'RowArray',
                'class' => 'Admin\\Snapshot\\Tiles',
            ),
        );
    }
