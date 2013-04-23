<?php
    namespace Cerceau\Database;

    class Database implements I\IDatabase {

        /**
         * @var I\IDriver
         */
        protected $Driver;

        /**
         * @var I\ISQLTemplator
         */
        protected $Templator;

        protected
            $rows,
            $lastQuery,
            $log = true,
            $spotId = null;

        /**
         * @param I\IConfig $Config
         * @param int $spotId
         */
        public function __construct( I\IConfig $Config, $spotId = null ){
            $this->Driver    = $Config->getDriver();
            $this->Templator = $Config->getTemplator();
            $this->spotId    = $spotId;
        }

        protected function apply( $method, $args ){
            $errorReporting = error_reporting( E_ALL ^ E_WARNING );
            try {
                if( empty( $args ))
                    throw new \Cerceau\Exception\Database\QueryLogicError( 'No arguments in '.__CLASS__.'::'. $method );
                if(!is_array( $args ))
                    throw new \Cerceau\Exception\Database\QueryLogicError( 'Wrong arguments in '.__CLASS__.'::'. $method );

                $tpl = array_shift( $args );
                if(!is_string( $tpl ))
                    throw new \Cerceau\Exception\Database\QueryLogicError( 'SQL template not found in '.__CLASS__.'::'. $method );

                $args = array_shift( $args );
                if( empty( $args ))
                    $args = array();
                if(!is_array( $args ))
                    throw new \Cerceau\Exception\Database\QueryLogicError( 'Wrong arguments in '.__CLASS__.'::'. $method );

                $this->lastQuery = $this->Templator->parseSQL( $tpl, $args, $this->spotId );

                $this->Driver->query( $this->lastQuery );
            }
            catch( \Cerceau\Exception\Database\Connection $E ){
                if( $this->log )
                    \Cerceau\System\Registry::instance()->Logger()->log( 'sql-connection-errors', $E->getMessage());
                return false;
            }
            catch( \Cerceau\Exception\Database\SQLQuery $E ){
                if( $this->log )
                    \Cerceau\System\Registry::instance()->Logger()->log( 'sql-errors', $E->getMessage() .' in query '."\n". $this->lastQuery());
                return false;
            }
            catch( \Exception $E ){
                throw $E;
            }
            error_reporting( $errorReporting );
            return true;
        }

        public function setLog( $log = true ){
            $oldVal = $this->log;
            $this->log = !!$log;
            return $oldVal;
        }

        public function toScalar( $value ){
            return $this->Driver->toScalar( $value );
        }

        public function escapeString( $value ){
            return $this->Driver->escapeString( $value );
        }

        public function equalExpression( $param, $value ){
            return $this->Driver->equalExpression( $param, $value );
        }

        public function insert(){
            if(!$this->apply( __METHOD__, func_get_args()))
                return false;

            return $this->Driver->getInsertId();
        }

        public function query(){
            return !!$this->apply( __METHOD__, func_get_args());
        }

        public function queryAffected(){
            if(!$this->apply( __METHOD__, func_get_args()))
                return false;

            return $this->Driver->getAffected();
        }

        public function selectField(){
            if(!$this->apply( __METHOD__, func_get_args()))
                return false;

            if( $record = $this->Driver->fetchRow())
                return array( $record[0] );
            return array();
        }

        public function selectRecord(){
            if(!$this->apply( __METHOD__, func_get_args()))
                return false;

            if( $record = $this->Driver->fetchRow( true ))
                return array( $record );
            return array();
        }

        protected function calculateRows(){
            if(
                strpos( $this->lastQuery, 'SQL_CALC_FOUND_ROWS' ) !== false &&
                $this->apply( __METHOD__, array( 'SELECT FOUND_ROWS()' )) &&
                $record = $this->Driver->fetchRow()
            ){
                $this->rows = $record[0];
            }else{
                $this->rows = null;
            }
        }

        public function selectColumn(){
            if(!$this->apply( __METHOD__, func_get_args()))
                return false;

            $out = array();
            while( $result = $this->Driver->fetchRow())
                $out[] = $result[0];

            $this->calculateRows();
            return $out;
        }

        public function selectTable(){
            if(!$this->apply( __METHOD__, func_get_args()))
                return false;

            $out = array();
            while( $result = $this->Driver->fetchRow( true ))
                $out[] = $result;

            $this->calculateRows();
            return $out;
        }

        public function selectIndexedColumn(){
            if(!$this->apply( __METHOD__, func_get_args()))
                return false;

            $out = array();
            while( $result = $this->Driver->fetchRow())
                $out[$result[0]] = $result[1];

            $this->calculateRows();
            return $out;
        }

        public function selectIndexedTable(){
            if(!$this->apply( __METHOD__, func_get_args()))
                return false;

            $out = array();
            while( $result = $this->Driver->fetchRow( true ))
                $out[reset( $result )] = $result;

            $this->calculateRows();
            return $out;
        }

        public function select2IndexedColumn(){
            if(!$this->apply( __METHOD__, func_get_args()))
                return false;

            $out = array();
            while( $result = $this->Driver->fetchRow())
                $out[$result[0]][$result[1]] = $result[2];

            $this->calculateRows();
            return $out;
        }

        public function select2IndexedTable(){
            if(!$this->apply( __METHOD__, func_get_args()))
                return false;

            $out = array();
            while( $result = $this->Driver->fetchRow( true )){
                $index1 = array_shift($result);
                $index2 = array_shift($result);
                $out[$index1][$index2] = $result;
            }

            $this->calculateRows();
            return $out;
        }

        public function lastQuery(){
            return $this->lastQuery;
        }

        public function rows(){
            return $this->rows;
        }

        public function close(){
            return $this->Driver->close();
        }
    }
