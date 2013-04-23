<?php
	namespace Cerceau\Data\I;

	interface IData {
        /**
         * @abstract
         */
		public function validate();

        /**
         * @abstract
         * @return bool
         */
        public function isChanged();
	}
	