<?php
	namespace Cerceau\Model\I;

	interface IRow {
        /**
         * @abstract
         * @param array $data
         * @return bool
         */
        public function load( array $data );

        /**
         * @abstract
         * @param array $data
         * @return bool
         */
        public function create( array $data );

        /**
         * @abstract
         * @param array $data
         * @param array|null $by
         * @return bool
         */
        public function update( array $data, array $by = null );

        /**
         * @abstract
         * @return mixed
         */
        public function result();
	}
	