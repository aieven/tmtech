<?php

    namespace Cerceau\Database\I;

    interface IDatabase {

        /**
         * @abstract
         * @param bool $log
         * @return mixed
         */
        public function setLog( $log = true );

        /**
         * @abstract
         * @param mixed $value
         * @return string
         */
        public function toScalar( $value );

        /**
         * @abstract
         * @param string $value
         * @return string
         */
        public function escapeString( $value );

        /**
         * @abstract
         * @param $param
         * @param $value
         * @return string
         */
        public function equalExpression( $param, $value );

        /**
         * @abstract
         * @return bool|int
         */
        public function insert();

        /**
         * @abstract
         * @return bool
         */
        public function query();

        /**
         * @abstract
         * @return bool|int
         */
        public function queryAffected();

        /**
         * @abstract
         * @return mixed
         */
        public function selectField();

        /**
         * @abstract
         * @return array
         */
        public function selectRecord();

        /**
         * @abstract
         * @return array
         */
        public function selectColumn();

        /**
         * @abstract
         * @return array
         */
        public function selectTable();

        /**
         * @abstract
         * @return array
         */
        public function selectIndexedColumn();

        /**
         * @abstract
         * @return array
         */
        public function selectIndexedTable();

        /**
         * @abstract
         * @return array
         */
        public function select2IndexedColumn();

        /**
         * @abstract
         * @return array
         */
        public function select2IndexedTable();

        /**
         * @abstract
         * @return string
         */
        public function lastQuery();

        /**
         * @abstract
         * @return int
         */
        public function rows();

        /**
         * @abstract
         * @return bool
         */
        public function close();
    }
