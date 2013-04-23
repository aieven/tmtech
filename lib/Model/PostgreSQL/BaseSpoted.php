<?php
    namespace Cerceau\Model\PostgreSQL;

    class BaseSpoted extends Base {

        protected $spotId;

        /**
         * @param $userId
         * @return int
         */
        public function setSpotId( $userId ){
            return $this->spotId = intval(( $userId - 1 ) / \Cerceau\Config\Constants::DATABASE_SPOT_SIZE ) + 1;
        }

        /**
         * @param null|string $name
         * @return \Cerceau\Database\I\IDatabase
         */
        protected function db( $name = null ){
            return \Cerceau\System\Registry::instance()->DatabaseSpotConnection()->get( $name ?: $this->db, $this->spotId );
        }
    }
