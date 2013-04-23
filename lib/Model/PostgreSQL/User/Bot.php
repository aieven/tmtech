<?php
    namespace Cerceau\Model\PostgreSQL\User;

	class Bot extends \Cerceau\Model\PostgreSQL\Base {

        const SQL_SELECT_BOT = <<<SQL
    -- SQL_SELECT_BOT
    SELECT instagram_id
      FROM {{ t("bots")}}
      WHERE request_available > 50
      {{ IF type }}
          AND {{ type }} = ANY (types)
      {{ END }}
      ORDER BY request_available DESC
      LIMIT 1
SQL;

        /**
         * @param array $conditions
         * @return array
         */
        public function selectBot( array $conditions ){
            return $this->db()->selectTable( self::SQL_SELECT_BOT, $conditions );
        }
    }
