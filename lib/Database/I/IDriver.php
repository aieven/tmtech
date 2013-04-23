<?php

    namespace Cerceau\Database\I;

    interface IDriver {

        /**
         * @param IConfig $Config
         */
        public function __construct( IConfig $Config );

        /**
         * @abstract
         * @param string $sql
         */
        public function query( $sql );

        /**
         * @abstract
         * @return bool|int
         */
        public function getInsertId();

        /**
         * @abstract
         * @return int
         */
        public function getAffected();

        /**
         * @abstract
         * @param bool $assoc
         * @return array
         */
        public function fetchRow( $assoc = false );

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
         * @return bool
         */
        public function close();
    }
