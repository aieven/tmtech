<?php
	namespace Cerceau\Model\I;

	interface IList extends ISpoted {

        /**
         * @abstract
         * @param string $key
         * @param array $data
         * @return int|bool
         */
        public function append( $key, array $data );

        /**
         * @abstract
         * @param string $key
         * @param int $idx
         * @return array|bool
         */
        public function get( $key, $idx );

        /**
         * @abstract
         * @param string $key
         * @param int $idx
         * @param array $data
         * @return bool
         */
        public function update( $key, $idx, array $data );

        /**
         * @abstract
         * @param $key
         * @param int $fromIdx
         * @param int $count
         * @return array
         */
        public function range( $key, $fromIdx, $count = null );

        /**
         * @abstract
         * @param $key
         * @return int
         */
        public function count( $key );
	}
	