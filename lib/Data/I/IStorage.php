<?php
    namespace Cerceau\Data\I;

    interface IStorage extends \ArrayAccess, IExportable {
        /**
         * @abstract
         * @param array $a
         * @return IStorage
         */
        public function fetch( array $a );

        /**
         * @abstract
         * @param array $a
         * @return IStorage
         */
        public function merge( array $a );

        /**
         * @abstract
         * @return array
         */
        public function keys();
    }
