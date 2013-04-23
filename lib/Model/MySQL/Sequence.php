<?php
    namespace Cerceau\Model\MySQL;

	class Sequence implements \Cerceau\Model\I\ISequence {
        protected
            $db = 'main',
            $name;

        public function __construct( $name ){
            if(!$name )
                throw new \LogicException( 'Name is not set in '. __CLASS__ );

            $this->name = $name;
        }

        const SQL_SEQUENCE_CREATE = <<<SQL
    -- SQL_SEQUENCE_CREATE
    INSERT INTO {{ t("sequences") }}
        SET `name` = {{ s(name) }},
            `current_id` = LAST_INSERT_ID(1)
SQL;
        const SQL_SEQUENCE_ALLOCATE_ID = <<<SQL
    -- SQL_SEQUENCE_ALLOCATE_ID
    UPDATE {{ t("sequences") }}
        SET `current_id` = LAST_INSERT_ID(`current_id` + 1)
        WHERE `name` = {{ s(name) }}
SQL;
        public function allocateId(){
            $id = $this->db()->insert(
                self::SQL_SEQUENCE_ALLOCATE_ID,
                array( 'name' => $this->name )
            );
            if(!$id ){
                $id = $this->db()->insert(
                    self::SQL_SEQUENCE_CREATE,
                    array( 'name' => $this->name )
                );
            }
            return $id;
        }

        const SQL_SEQUENCE_GET_ID = <<<SQL
    -- SQL_SEQUENCE_GET_ID
    SELECT `current_id`
        FROM {{ t("sequences") }}
        WHERE `name` = {{ s(name) }}
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
