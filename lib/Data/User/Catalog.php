<?php

    namespace Cerceau\Data\User;

    class Catalog extends \Cerceau\Data\Base\Row {

        protected static $fieldsOptions = array(
            'user_id' => array(
                'Int',
            ),
        );

        /**
         * @var \Cerceau\Model\PostgreSQL\User\Catalog
         */
        protected $Model;

        public function initialize(){
            $this->Model = new \Cerceau\Model\PostgreSQL\User\Catalog( 'main' );
        }

        public function selectAll(){
            return $this->Model->selectAll( $this->export());
        }
    }
