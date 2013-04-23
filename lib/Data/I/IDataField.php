<?php
	namespace Cerceau\Data\I;

	interface IDataField extends IData {
        /**
         * @abstract
         * @return mixed
         */
        public function toScalar();

        /**
         * @abstract
         * @return mixed
         */
        public function get();

        /**
         * @abstract
         * @param $value
         * @param bool $fetch
         * @return mixed
         */
        public function set( $value, $fetch = true );

        /**
         * @abstract
         * @param bool $default
         * @return mixed
         */
        public function reset( $default = true );

        /**
         * @abstract
         *
         */
        public function resetChanged();

        /**
         * @abstract
         * @param IStorage $Storage
         */
        public function resetStorage( IStorage $Storage );
	}
	