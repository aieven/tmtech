<?php
    namespace Cerceau\Database\Driver;

    use \Cerceau\Exception\Database as E;

	class PostgreSQL extends Base {

        /**
         * @var \Cerceau\Database\I\IConfig
         */
        private $Config;

        /**
         * @var resource
         */
        private
            $connection,
            $lastQuery;

        /**
         * @param \Cerceau\Database\I\IConfig $Config
         */
		public function __construct( \Cerceau\Database\I\IConfig $Config ){
            $this->Config    = $Config;
        }

        public function __destruct(){
            if( $this->connection )
                pg_close( $this->connection );
        }

        private function connect(){
            if( $this->connection )
                return $this->connection;
            try {
                $dbName = $this->Config->get( 'dbname' );
                $connectionString =
                    'host='. $this->Config->get( 'host' ) .
                    ' port='. $this->Config->get( 'port' ) .
                    ' user='. $this->Config->get( 'user' ) .
                    ( $dbName ? ' dbname='. $dbName : '' ) .
                    ' password='. $this->Config->get( 'password' ) .
                    ' options=\'--client_encoding='. $this->Config->get( 'charset' ) .'\'';
                $this->connection = pg_connect( $connectionString );
                if(!$this->connection )
                    throw new E\Connection( 'Cannot connect to Postgres database '. $connectionString );
            }
            catch( \Exception $E ){
                $this->connection = null;
                throw $E;
            }
            return $this->connection;
        }

        /**
         * @param $sql
         * @throws \Cerceau\Exception\Database\SQLQuery
         */
		public function query( $sql ){
//            \Cerceau\System\Registry::instance()->Logger()->log( 'sql', $sql );
            $this->lastQuery = pg_query( $this->connect(), $sql );
            if(!$this->lastQuery )
                throw new E\SQLQuery( pg_last_error( $this->connect()));
        }

        /**
         * SQL must contain RETURNING <id field>
         *
         * @return bool|int
         */
        public function getInsertId(){
            $row = pg_fetch_row( $this->lastQuery );
            if(!$row )
                return false;
            return reset( $row );
        }

        /**
         * @return int
         */
        public function getAffected(){
            return pg_affected_rows( $this->lastQuery );
        }

        /**
         * @param bool $assoc
         * @return array
         * @throws \Cerceau\Exception\Database\QueryLogicError
         */
        public function fetchRow( $assoc = false ){
            if(!$this->lastQuery )
                throw new E\QueryLogicError( 'Must execute query before fetch row' );
            if( $assoc )
                return pg_fetch_assoc( $this->lastQuery );
            else
                return pg_fetch_row( $this->lastQuery );
        }

        /**
         * @param string $value
         * @return string
         */
        public function escapeString( $value ){
            return pg_escape_string( $this->connect(), strval( $value ));
        }

        /**
         * @return bool
         */
        public function close(){
            if( $this->connection ){
                pg_close( $this->connection );
                $this->connection = null;
            }
            return true;
        }
    }
	