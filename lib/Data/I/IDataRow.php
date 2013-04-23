<?php
	namespace Cerceau\Data\I;

	interface IDataRow extends IData, IStorage {
        /**
         * Fetches the Storage into Row and back, filtered Row into source Storage
         *
         * @abstract
         * @param IStorage $Storage
         * return IDataRow
         */
        public function set( IStorage $Storage );

        /**
         * @abstract
         * @param array $data
         * @return array
         */
        public function fieldsFilter( array $data );
	}
	