<?php
    namespace Cerceau\Model\PostgreSQL;

	class Sequence implements \Cerceau\Model\I\ISequence {
        protected
            $db = 'main',
            $name;

        public function __construct( $name, $db = null ){
            if(!$name )
                throw new \LogicException( 'Name is not set in '. __CLASS__ );

            $this->name = $name;

            if( $db )
                $this->db = $db;
        }

        const SQL_SEQUENCE_CREATE = <<<SQL
    -- SQL_SEQUENCE_CREATE
    CREATE SEQUENCE {{ name }}_sequence;
    SELECT nextval('{{ name }}_sequence');
SQL;
        const SQL_SEQUENCE_ALLOCATE_ID = <<<SQL
    -- SQL_SEQUENCE_ALLOCATE_ID
    SELECT nextval('{{ name }}_sequence');
SQL;
        public function allocateId(){
            $id = $this->db()->selectField(
                self::SQL_SEQUENCE_ALLOCATE_ID,
                array( 'name' => $this->name )
            );
            if( empty( $id )){
                $id = $this->db()->selectField(
                    self::SQL_SEQUENCE_CREATE,
                    array( 'name' => $this->name )
                );
            }
            return intval( reset( $id ));
        }

        const SQL_SEQUENCE_GET_ID = <<<SQL
    -- SQL_SEQUENCE_GET_ID
    SELECT currval('{{ name }}_sequence');
SQL;
        public function lastId(){
            $id = $this->db()->selectField(
                self::SQL_SEQUENCE_GET_ID,
                array( 'name' => $this->name )
            );
            if(!$id )
                return 0;
            return intval( reset( $id ));
        }


        /**
         * @param null|string $name
         * @return \Cerceau\Database\I\IDatabase
         */
        final protected function db( $name = null ){
            return \Cerceau\System\Registry::instance()->DatabaseConnection()->get( $name ? $name : $this->db );
        }
    }
