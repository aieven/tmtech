<?php
	namespace Cerceau\Data\User;

	interface IAuth extends \Cerceau\Data\I\IStorage {
        /**
         * @abstract
         * @return bool
         */
        public function isReal();

        /**
         * @abstract
         * @return int
         */
        public function getId();

        /**
         * @param int $action
         * @return bool
         */
        public function can( $action );
    }
	