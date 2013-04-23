<?php
    namespace Cerceau\Model\MySQL;

	class Queue implements \Cerceau\Model\I\IQueue {
        protected
            $db = 'main',
            $queueId,
            $table;

        public function __construct( $name, $db = null ){
            if(!$name )
                throw new \LogicException( 'Name is not set in '. __CLASS__ );
            if( $db )
                $this->db = $db;

            $this->table = 'queue_'. $name;
        }

        const SQL_QUEUE_CREATE = <<<SQL
    -- SQL_QUEUE_CREATE
    CREATE TABLE IF NOT EXISTS {{ t(table) }} (
      `queue_id` int(10) unsigned DEFAULT NULL,
      `data` text NOT NULL,
      `updated` datetime NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
SQL;
        const SQL_QUEUE_PUSH = <<<SQL
    -- SQL_QUEUE_PUSH
    INSERT INTO {{ t(table) }}
        SET `data` = {{ s(data) }},
            `updated` = NOW()
SQL;
        public function push( $data, $options = array()){
            $data = array(
                'table' => $this->table,
                'data' => $data,
            );
            return $this->db()->query(
                self::SQL_QUEUE_PUSH,
                $data
            );
        }

        const SQL_QUEUE_PUSH_STACK = <<<SQL
    -- SQL_QUEUE_PUSH_STACK
    INSERT INTO {{ t(table) }} ( data, updated ) VALUES
    {{ BEGIN items }}
    {{ UNLESS _first }},{{ END }}({{ s(data) }}, NOW())
    {{ END }}
SQL;
        public function pushStack( $item ){
            return $this->db()->query(
                self::SQL_QUEUE_PUSH_STACK,
                array(
                    'table' => $this->table,
                    'items' => func_get_args(),
                )
            );
        }


        const SQL_QUEUE_SET_LOCK = <<<SQL
    -- SQL_QUEUE_SET_LOCK
    UPDATE {{ t(table) }}
        SET `queue_id` = {{ i(queue_id) }},
            `updated` = NOW()
        WHERE ISNULL(`queue_id`)
        LIMIT {{ i(count) }}
SQL;
        const SQL_QUEUE_LOAD = <<<SQL
    -- SQL_QUEUE_LOAD
    SELECT `data`
        FROM {{ t(table) }}
        WHERE `queue_id` = {{ i(queue_id) }}
SQL;
        const SQL_QUEUE_CLEAR = <<<SQL
    -- SQL_QUEUE_CLEAR
    DELETE FROM {{ t(table) }}
        WHERE `queue_id` = {{ i(queue_id) }}
SQL;
        public function pull( $count = 1 ){
            $data = array(
                'table' => $this->table,
                'queue_id' => $this->getId(),
                'count' => $count,
            );
            $Database = $this->db();
            if(!$Database->queryAffected(
                self::SQL_QUEUE_SET_LOCK,
                $data
            ))
                return false;
            $load = $Database->selectColumn(
                self::SQL_QUEUE_LOAD,
                $data
            );
            if( $load ){
                $Database->query(
                    self::SQL_QUEUE_CLEAR,
                    $data
                );
            }
            return $load;
        }

        const SQL_QUEUE_LEN = <<<SQL
    -- SQL_QUEUE_LEN
    SELECT COUNT(*) FROM {{ t(table) }}
SQL;
        public function len(){
            $count = $this->db()->selectField(
                self::SQL_QUEUE_LEN,
                array( 'table' => $this->table, )
            );
            if(!$count )
                return false;
            return intval( reset( $count ));
        }

        /**
         * @return int
         */
        final protected function getId(){
            if(!$this->queueId ){
                $Sequence = new Sequence( $this->table );
                $this->queueId = $Sequence->allocateId();
            }
            return $this->queueId;
        }

        /**
         * @param null|string $name
         * @return \Cerceau\Database\I\IDatabase
         */
        final protected function db( $name = null ){
            return \Cerceau\System\Registry::instance()->DatabaseConnection()->get( $name ? $name : $this->db );
        }
    }
