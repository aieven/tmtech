<?php
    namespace Cerceau\Model\PostgreSQL\Admin\Snapshot;

    class Catalog extends \Cerceau\Model\PostgreSQL\Base {

        const SQL_SELECT_LAST_SNAPSHOT = <<<SQL
    -- SQL_SELECT_LAST_SNAPSHOT
    SELECT *
      FROM {{ t("snapshots") }}
      ORDER BY created DESC
      LIMIT 1
SQL;
        public function selectLastSnapshot( $conditions = array()){
            return $this->db()->selectTable( self::SQL_SELECT_LAST_SNAPSHOT, $conditions );
        }

        const SQL_SELECT_PUBLISHED = <<<SQL
    -- SQL_SELECT_PUBLISHED
    SELECT *
      FROM {{ t("snapshots") }}
      WHERE published = 1
      ORDER BY created DESC
      LIMIT 1
SQL;
        public function selectPublished(){
            return $this->db()->selectRecord( self::SQL_SELECT_PUBLISHED );
        }

        const SQL_PUBLISH_SNAPSHOT = <<<SQL
    -- SQL_PUBLISH_SNAPSHOT
    SELECT *
      FROM publish_snapshot( {{ i(snapshot_id) }});
SQL;
        public function publishSnapshot( $conditions ){
            return $this->db()->selectField( self::SQL_PUBLISH_SNAPSHOT, $conditions );
        }
    }