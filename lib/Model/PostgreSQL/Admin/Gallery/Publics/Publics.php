<?php
    namespace Cerceau\Model\PostgreSQL\Admin\Gallery\Publics;

    class Publics extends \Cerceau\Model\PostgreSQL\Base {

        const SQL_ADD_PUBLIC = <<<SQL
    -- SQL_ADD_PUBLIC
    SELECT insert_gallery_public(
      {{ i(gallery_id) }}, {{ s(username) }}, {{ s(profile_picture) }}
    ) as public_id
SQL;

        /**
         * @param array $conditions
         * @return array
         */
        public function addPublic( array $conditions ){
            return $this->db()->selectRecord( self::SQL_ADD_PUBLIC, $conditions );
        }

        const SQL_LOAD_PUBLIC = <<<SQL
    -- SQL_LOAD_PUBLIC
    SELECT *
      FROM {{ t("gallery_publics") }}
      WHERE public_id = {{ i(public_id) }}
SQL;
        /**
         * @param array $conditions
         * @return array
         */
        public function loadPublic( array $conditions ){
            return $this->db()->selectRecord( self::SQL_LOAD_PUBLIC, $conditions );
        }

        const SQL_DELETE_PUBLIC = <<<SQL
    -- SQL_DELETE_PUBLIC
    UPDATE {{ t("gallery_publics") }}
      SET deleted = TRUE
      WHERE public_id = {{ i(public_id) }};
SQL;
        /**
         * @param array $conditions
         * @return array
         */
        public function deletePublic( array $conditions ){
            return $this->db()->query( self::SQL_DELETE_PUBLIC, $conditions );
        }

        const SQL_ADD_PUBLIC_PHOTOS = <<<SQL
    -- SQL_ADD_PUBLIC_PHOTOS
    INSERT INTO {{ t("media") }}
      ( public_id, instagram_media_id, url, comments_count, likes_count, datetime, data ) VALUES
         {{ BEGIN media }}
           {{ UNLESS _first }},{{ END }} ( {{ i(public_id) }}, {{ s(media_id) }}, {{ s(url) }}, {{ i(comments) }}, {{ i(likes) }}, {{ i(datetime) }}, {{ s(data) }} )
         {{ END media }};
SQL;

        /**
         * @param array $conditions
         * @return array
         */
        public function addPublicPhotos( array $conditions ){
            return $this->db()->query( self::SQL_ADD_PUBLIC_PHOTOS, $conditions );
        }

        const SQL_UPDATE_PUBLIC = <<<SQL
    -- SQL_UPDATE_PUBLIC
    UPDATE {{ t("gallery_publics") }}
      SET full_name = {{ s(full_name) }}
      WHERE public_id = {{ i(public_id) }}
SQL;

        /**
         * @param array $conditions
         * @return array
         */
        public function updatePublic( array $conditions ){
            return $this->db()->query( self::SQL_UPDATE_PUBLIC, $conditions );
        }

        const SQL_UPDATE_PARSED_PUBLIC = <<<SQL
    -- SQL_UPDATE_PARSED_PUBLIC
    UPDATE {{ t("gallery_publics") }}
      SET full_name = {{ s(full_name) }},
          instagram_id = {{ i(instagram_id) }},
          profile_picture = {{ s(profile_picture) }},
          followers = {{ i(followers) }}
      WHERE public_id = {{ i(public_id) }}
      RETURNING *
SQL;

        /**
         * @param array $conditions
         * @return array
         */
        public function updateParsedPublic( array $conditions ){
            return $this->db()->selectRecord( self::SQL_UPDATE_PARSED_PUBLIC, $conditions );
        }

        const SQL_UPDATE_NOT_PARSED_PUBLIC = <<<SQL
    -- SQL_UPDATE_PARSED_PUBLIC
    UPDATE {{ t("gallery_publics") }}
      SET full_name = 'parse_error'
      WHERE public_id = {{ i(public_id) }}
      RETURNING *
SQL;

        /**
         * @param array $conditions
         * @return array
         */
        public function updateNotParsedPublic( array $conditions ){
            return $this->db()->selectRecord( self::SQL_UPDATE_NOT_PARSED_PUBLIC, $conditions );
        }
    }
