<?php
    namespace Cerceau\Data\Admin\Gallery\Publics;

    class Catalog extends \Cerceau\Data\Base\Row {
        protected static $db = 'main';

        protected function initialize(){
            self::$fieldsOptions = array(
                'gallery_id' => array(
                    'Int',
                ),
                'limit' => array(
                    'Int',
                    'default' => 20,
                ),
                'max_datetime' => array(
                    'Int',
                ),
                'min_datetime' => array(
                    'Int',
                ),
                'api' => array(
                    'Int',
                ),
            );
            $this->Model = new \Cerceau\Model\PostgreSQL\Admin\Gallery\Publics\Catalog();
            parent::initialize();
        }
        /**
         * @var \Cerceau\Model\PostgreSQL\Admin\Gallery\Publics\Catalog
         */
        protected $Model;

        /**
         * @return array
         */

        public function selectPublics(){
            return $this->Model->selectPublics( $this->export());
        }

        public function selectPublicsMedia(){
            $media = $this->Model->selectPublicsMedia( $this->export());
            if( false === $media )
                return false;

            $nextMinDatetime = reset( $media );
            $nextMaxDatetime = end( $media );

            $pagination = array(
                'next_min_datetime' => $nextMinDatetime['datetime'],
                'next_max_datetime' => $nextMaxDatetime['datetime'],
            );

            foreach( $media as &$data )
                $data = unserialize( $data['data'] );

            return array( 'media' => $media , 'pagination' => $pagination );
        }
    }

