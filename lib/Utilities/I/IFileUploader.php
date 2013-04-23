<?php
    namespace Cerceau\Utilities\I;

    interface IFileUploader {
        /**
         * @abstract
         * @return array
         */
        public function fetch();

        /**
         * @abstract
         * @return string
         */
        public function getTempPath();

        /**
         * @abstract
         * @param array $data
         */
        public function upload( \ArrayAccess $data );
    }
	