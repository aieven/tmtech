<?php
    namespace Cerceau\Model\PostgreSQL\Admin\Gallery\Publics;

    class Catalog extends \Cerceau\Model\PostgreSQL\Base {

        const SQL_SELECT_PUBLICS = <<<SQL
    -- SQL_SELECT_PUBLICS
    SELECT  public_id,
            username,
            instagram_id,
            profile_picture,
            full_name,
            followers,
            likes,
            comments,
            photos
      FROM {{ t("gallery_publics") }}
      WHERE gallery_id = {{ i(gallery_id) }}
        AND deleted IS FALSE
        {{ IF api}}
        AND instagram_id IS NOT NULL
        {{ END }}
      GROUP BY public_id
      ORDER BY full_name
SQL;

        /**
         * @param array $conditions
         * @return array
         */
        public function selectPublics( array $conditions ){
            return $this->db()->selectTable( self::SQL_SELECT_PUBLICS, $conditions );
        }

        const SQL_SELECT_PUBLICS_MEDIA = <<<SQL
    -- SQL_SELECT_PUBLICS_MEDIA
    SELECT  m.data, datetime
      FROM {{ t("gallery_publics") }} p
      INNER JOIN {{ t("media") }} m ON p.public_id = m.public_id
      WHERE gallery_id = {{ i(gallery_id) }}
        AND p.deleted IS FALSE
      {{ IF max_datetime }}
        AND datetime < {{ i(max_datetime) }}
      {{ END }}
      {{ IF min_datetime }}
        AND datetime > {{ i(min_datetime) }}
      {{ END }}
      {{ IF api }}
        AND p.instagram_id IS NOT NULL
      {{ END }}
      ORDER BY datetime DESC
      {{ UNLESS min_datetime }}
      LIMIT {{ i(limit) }}
      {{ END}}
SQL;

        /**
         * @param array $conditions
         * @return array
         */
        public function selectPublicsMedia( array $conditions ){
            return $this->db()->selectTable( self::SQL_SELECT_PUBLICS_MEDIA, $conditions );
        }
    }
