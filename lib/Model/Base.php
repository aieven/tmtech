<?php
    namespace Cerceau\Model;

    class Base implements \Cerceau\Model\I\IModel {
        protected
            $db = 'main',
            $table = null,
            $spotId = null,
            $lastResult = null,

            $transactionLevel = 0;

        public function __construct( $db = null, $table = null, $spotId = null ){
            if( $db )
                $this->db = $db;
            if( $table )
                $this->table = $table;
            if( $spotId )
                $this->spotId = $spotId;
        }

        protected static $SQL_BASE_LOAD = <<<SQL
    -- SQL_BASE_LOAD {{ t( table, spot ) }}
    SELECT *
      FROM {{ t( table, spot ) }}
      WHERE {{ conditions }}
      LIMIT 1
SQL;
        public function load( array $data ){
            // reset result
            $this->lastResult = null;
            // data and table must be present
            if(!$data || !$this->table )
                return false;

            $Database = $this->db();
            // concatenate conditions
            $conditions = array();
            foreach( $data as $k => $value ){
                $condition = $Database->equalExpression( $k, $value );
                if( $condition )
                    $conditions[] = $condition;
            }
            if( empty( $conditions ))
                return false;
            // select row
            $result = $Database->selectRecord(
                static::$SQL_BASE_LOAD,
                array(
                    'table' => $this->table,
                    'spot' => $this->spotId,
                    'conditions' => implode( ' AND ', $conditions ),
                )
            );
            if(!$result )
                return false;
            $this->lastResult = reset( $result );
            return true;
        }

        protected static $SQL_BASE_CREATE = <<<SQL
    -- SQL_BASE_CREATE {{ t( table, spot ) }}
    INSERT INTO {{ t( table, spot ) }}
      ( {{ keys }} ) VALUES
      ( {{ vals }} )
SQL;
        public function create( array $data ){
            // reset result
            $this->lastResult = null;
            // data and table must be present
            if( empty( $data ) || !$this->table )
                return false;

            $Database = $this->db();
            // concatenate keys and values
            $valuesString = $keysString = '';
            foreach( $data as $k => $value ){
                // scalar value
                $v = $Database->toScalar( $value );
                // null means no value
                if(!is_null( $v )){
                    $valuesString .= $v .',';
                    $keysString   .= $k .',';
                }
            }
            if(!$keysString )
                return false;
            // insert row
            $result = $Database->insert(
                static::$SQL_BASE_CREATE,
                array(
                    'table' => $this->table,
                    'spot' => $this->spotId,
                    'keys' => rtrim( $keysString, ',' ),
                    'vals' => rtrim( $valuesString, ',' ),
                )
            );
            if( false === $result )
                return false;
            // save result
            $this->lastResult = $result;
            return true;
        }


        protected static $SQL_BASE_UPDATE = <<<SQL
    -- SQL_BASE_UPDATE {{ t( table, spot ) }}
    UPDATE {{ t( table, spot ) }}
      SET {{ updates }}
      WHERE {{ conditions }}
SQL;
        public function update( array $data, array $by = null ){
            // reset result
            $this->lastResult = null;
            // data and table must be present
            // condition data must be present in var $by by default
            if(!$data || !$by || !$this->table )
                return false;

            $Database = $this->db();
            // concatenate data for update
            $updates = array();
            foreach( $data as $k => $value ){
                $update = $Database->equalExpression( $k, $value );
                if( $update )
                    $updates[] = $update;
            }
            // concatenate conditions
            $conditions = array();
            foreach( $by as $k => $value ){
                $condition = $Database->equalExpression( $k, $value );
                if( $condition )
                    $conditions[] = $condition;
            }
            if( empty( $updates ) || empty( $conditions ))
                return false;
            // update row
            $this->lastResult = $Database->queryAffected(
                static::$SQL_BASE_UPDATE,
                array(
                    'table' => $this->table,
                    'spot' => $this->spotId,
                    'updates' => implode( ', ', $updates ),
                    'conditions' => implode( ' AND ', $conditions ),
                )
            );
            return !!$this->lastResult;
        }

        final public function begin(){
            if(!$this->transactionLevel )
                $this->db()->query( 'BEGIN;' );
            $this->transactionLevel++;
        }

        final public function commit(){
            $this->transactionLevel--;
            if(!$this->transactionLevel )
                $this->db()->query( 'COMMIT;' );
        }

        final public function rollback(){
            $this->db()->query( 'ROLLBACK;' );
            $this->transactionLevel = 0;
        }

        final public function result(){
            return $this->lastResult;
        }

        public function closeConnection(){
            return $this->db()->close();
        }

        /**
         * @param null|string $name
         * @return \Cerceau\Database\I\IDatabase
         */
        protected function db( $name = null ){
            return \Cerceau\System\Registry::instance()->DatabaseConnection()->get( $name ? $name : $this->db );
        }
    }
