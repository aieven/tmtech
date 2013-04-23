<?php
    namespace Cerceau\Model\PostgreSQL\User;

    class CatalogBot extends \Cerceau\Model\PostgreSQL\Base {

        const SQL_SELECT_BOT_DATAS = <<<SQL
    -- SQL_SELECT_BOT_DATAS
    SELECT *
      FROM {{ t("bots")}}
      LEFT JOIN {{ t("bot_queries") }} q USING (instagram_id)
      WHERE 1 = 1
      {{ IF instagram_id }}
      AND instagram_id = {{ i(instagram_id) }}
      {{ END }}
      {{ IF type }}
      AND {{ type }} = ANY(types)
      {{ END }}
SQL;

        /**
         * @param array $conditions
         * @return array
         */
        public function selectBotData( $conditions = array() ){
            return $this->db()->selectTable( self::SQL_SELECT_BOT_DATAS, $conditions );
        }

        const SQL_SELECT_BOTS = <<<SQL
    -- SQL_SELECT_BOTS
    SELECT *
      FROM {{ t("bots") }}
      {{ IF type }}
      WHERE {{ type }} = ANY(types)
      {{ END }}
      ORDER BY request_available DESC
SQL;

        public function selectBots( array $conditions ){
            return $this->db()->selectTable( self::SQL_SELECT_BOTS, $conditions );
        }
    }