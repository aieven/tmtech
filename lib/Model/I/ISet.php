<?php
	namespace Cerceau\Model\I;

	interface ISet extends ISpoted {

        /**
         * @abstract
         * @param $key
         * @param $member
         * @return bool
         */
        public function append( $key, $member );

        /**
         * @abstract
         * @param $key
         * @param $member
         * @return bool
         */
        public function exists( $key, $member );

        /**
         * @abstract
         * @param $key
         * @param $member
         * @return bool
         */
        public function remove( $key, $member );

        /**
         * @abstract
         * @param $key
         * @return bool
         */
        public function clear( $key );

        /**
         * @abstract
         * @param $key
         * @return array
         */
        public function members( $key );

        /**
         * @abstract
         * @param $key
         * @return int
         */
        public function count( $key );
	}
	