<?php
	namespace Cerceau\Data\I;

	interface IExportable {
        /**
         * @abstract
         * @return array
         */
        public function export();
	}
	