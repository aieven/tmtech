<?php
	namespace Cerceau\Model\I;

	interface IModel extends IRow {
        public function begin();
        public function commit();
        public function rollback();
	}
