<?php
    namespace Cerceau\Data\Admin\Snapshot;

    class Snapshot extends \Cerceau\Data\Base\DbRow {

        protected static $modelName = 'PostgreSQL\\Base';
        protected static $db = 'main';
        protected static $table = 'snapshots';

        protected function initialize(){
            self::$fieldsOptions = array(
                'snapshot_id' => array(
                    'Int',
                    'const',
                    'load',
                    'autoIncrement',
                ),
                'snapshot_data' => array(
                    'Row',
                    'class' => 'Admin\\Snapshot\\Data'
                ),
                'published' => array(
                    'Int',
                    'default' => 0
                ),
                'created' => array(
                    'Int',
                    'default' => \Cerceau\System\Registry::instance()->Date()->timestamp(),
                ),
            );
            parent::initialize();
        }
    }