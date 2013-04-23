<?php
	namespace Cerceau\Model\I;

	interface ISequence {
        /**
         * @abstract
         * @return int
         */
        public function allocateId();

        /**
         * @abstract
         * @return int
         */
        public function lastId();
	}
	