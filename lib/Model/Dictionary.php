<?php
    namespace Cerceau\Model;

	class Dictionary {
        protected
            $db = 'main',
            $table = null;

        public function __construct( $db = null, $table = null ){
            if( $db )
                $this->db = $db;
            if( $table )
                $this->table = $table;
        }

        protected static $SQL_DICTIONARY_SELECT = <<<SQL
    -- SQL_DICTIONARY_SELECT {{ t(table) }}
    SELECT *
      FROM {{ t(table) }}
      {{ IF conditions }}
        WHERE {{ conditions }}
      {{ END }}
SQL;
        public function select( array $by = array()){
            // data and table must be present
            if(!$this->table )
                return array();

            $Database = $this->db();
            // concatenate conditions
            $conditions = array();
            foreach( $by as $k => $value ){
                $condition = $Database->equalExpression( $k, $value );
                if( $condition )
                    $conditions[] = $condition;
            }
            // select row
            return $Database->selectIndexedColumn(
                static::$SQL_DICTIONARY_SELECT,
                array(
                    'table' => $this->table,
                    'conditions' => implode( ' AND ', $conditions ),
                )
            );
        }

        /**
         * @param null|string $name
         * @return \Cerceau\Database\I\IDatabase
         */
        final protected function db( $name = null ){
            return \Cerceau\System\Registry::instance()->DatabaseConnection()->get( $name ? $name : $this->db );
        }
    }
