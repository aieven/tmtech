<?php
	namespace Cerceau\Model\I;

	interface IHashAuthorizer extends IAuthorizer {

        /**
         * Set one field
         *
         * @abstract
         * @param $key
         * @param $field
         * @param $value
         * @return bool
         */
        public function setField( $key, $field, $value );

        /**
         * Set one field
         *
         * @abstract
         * @param $key
         * @param $field
         * @param $value
         * @return bool
         */
        public function incrField( $key, $field, $value );

        /**
         * Get one field
         *
         * @abstract
         * @param $key
         * @param $field
         * @return bool
         */
        public function getField( $key, $field );

        /**
         * Delete one or more fields
         *
         * @abstract
         * @param $key
         * @param $field
         * @return bool
         */
        public function removeField( $key, $field );
	}
	