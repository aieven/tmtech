<?php
    namespace Cerceau\Model\PostgreSQL\User;

	class Catalog extends \Cerceau\Model\PostgreSQL\Base {

        const SQL_SELECT_ALL_USERS = <<<SQL
    -- SQL_SELECT_ALL_USERS
    SELECT admin_id, email, privileges
      FROM {{ t("admins")}}
      ORDER BY email
SQL;

        /**
         * @param array $conditions
         * @return array
         */
        public function selectAll( array $conditions ){
            return $this->db()->selectTable( self::SQL_SELECT_ALL_USERS, $conditions );
        }
    }
