<?php
	namespace Cerceau\Model\I;

	interface IQueue {

        /**
         * @param $data
         * @param array $options
         * @return bool
         */
        public function push( $data, $options = array());

        /**
         * @param array $item
         * @return bool
         */
        public function pushStack( $item );

        /**
         * @abstract
         * @param int $count
         * @return bool|array
         */
        public function pull( $count = 1 );

        /**
         * @abstract
         * @return int
         */
        public function len();
	}
	