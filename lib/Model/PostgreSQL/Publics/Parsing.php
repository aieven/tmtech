<?php
    namespace Cerceau\Model\PostgreSQL\Publics;

    class Parsing extends \Cerceau\Model\PostgreSQL\Base {
        /**
         * table - gallery_publics
         * sub - media
         *
         * table - people_publics
         * sub - people_statistics
         */
        protected static $tableMedia = array(
            'gallery_publics' => 'media',
            'people_publics'  => 'people_statistics',
        );

        protected static $haveMedia = array(
            'gallery_publics' => true,
            'people_publics'  => false,
        );

        protected $data;

        public function __construct( $db = null, $table = null, $spotId = null ){
            parent::__construct( $db, $table, $spotId );
            $this->data = array(
                'table' => $table,
                'table_media' => static::$tableMedia[$table],
                'have_media' => static::$haveMedia[$table],
            );
        }

        const SQL_SELECT_NEW = <<<SQL
    -- SQL_SELECT_NEW
    SELECT  p.public_id,
            p.instagram_id,
            min(am.datetime) as min_datetime
      FROM {{ t(table) }} p
      LEFT JOIN {{ t(table_media) }} am USING(public_id)
      WHERE p.deleted = FALSE
        AND p.parsed_all_old_media = FALSE
        AND p.instagram_id IS NOT NULL
      GROUP BY p.public_id
SQL;
        public function selectNew(){
            return $this->db()->selectTable( self::SQL_SELECT_NEW, $this->data );
        }

        const SQL_SELECT_PARSED = <<<SQL
    -- SQL_SELECT_PARSED
    SELECT  p.public_id,
            p.instagram_id,
            max(am.datetime) as max_datetime
      FROM {{ t(table_media) }} am
      INNER JOIN {{ t(table) }} p USING(public_id)
      WHERE p.deleted = FALSE
        AND p.parsed_all_old_media = TRUE
      GROUP BY p.public_id
SQL;
        public function selectParsed(){
            return $this->db()->selectTable( self::SQL_SELECT_PARSED, $this->data );
        }

        const SQL_SELECT_LAST_MEDIA = <<<SQL
    -- SQL_SELECT_LAST_MEDIA
    SELECT instagram_media_id
      FROM {{ t(table_media) }}
      WHERE public_id = {{ i(public_id) }}
      ORDER BY datetime DESC
      LIMIT 60
SQL;
        public function selectLastMedia( $conditions ){
            return $this->db()->selectColumn( self::SQL_SELECT_LAST_MEDIA, $this->data + $conditions );
        }

        const SQL_SET_FOLLOWERS = <<<SQL
    -- SQL_SET_FOLLOWERS
    UPDATE {{ t(table) }}
      SET followers = {{ i(followers) }}
      WHERE public_id = {{ i(public_id) }}
SQL;
        public function setFollowers( $conditions ){
            return $this->db()->query( self::SQL_SET_FOLLOWERS, $this->data + $conditions );
        }

        const SQL_CREATE_MEDIA = <<<SQL
    -- SQL_CREATE_MEDIA
    INSERT INTO {{ t(table_media) }}
      (
        public_id, instagram_media_id, comments_count, likes_count, datetime
        {{ IF have_media }}, data {{ END }}
      ) VALUES
    {{ BEGIN media }}
      {{ UNLESS _first }},{{ END }} (
        {{ i(public_id) }},
        {{ s(instagram_media_id) }},
        {{ i(comments) }},
        {{ i(likes) }},
        {{ i(datetime) }}
        {{ IF have_media }}, {{ s(data) }} {{ END }}
      )
    {{ END }}
SQL;
        public function createMedia( $conditions ){
            if( $this->data['have_media'] ){
                foreach( $conditions['media'] as &$media )
                    $media['have_media'] = true;
            }
            return $this->db()->query( self::SQL_CREATE_MEDIA, $this->data + $conditions );
        }

        const SQL_UPDATE_MEDIA = <<<SQL
    -- SQL_UPDATE_MEDIA
    UPDATE {{ t(table_media) }}
      SET likes_count = {{ i(likes) }},
          comments_count = {{ i(comments) }}
          {{ IF have_media }}, data = {{ s(data) }}{{ END }}
      WHERE instagram_media_id = {{ s(instagram_media_id) }};
SQL;
        public function updateMedia( $conditions ){
            return $this->db()->query( self::SQL_UPDATE_MEDIA , $this->data + $conditions );
        }

        const SQL_DELETE_MEDIA = <<<SQL
    -- SQL_DELETE_MEDIA
    UPDATE {{ t(table_media) }}
      SET deleted = TRUE
      WHERE instagram_media_id IN {{ sa(instagram_media_ids) }};
SQL;
        public function deleteMedia( $conditions ){
            return $this->db()->query( self::SQL_DELETE_MEDIA , $this->data + $conditions );
        }

        const SQL_COMPLETE_PARSE_OLD_MEDIA = <<<SQL
    -- SQL_COMPLETE_PARSE_OLD_MEDIA
    UPDATE {{ t(table) }}
      SET parsed_all_old_media = TRUE
      WHERE public_id = {{ i(public_id) }};
SQL;
        public function completeParseOldMedia( $conditions ){
            return $this->db()->query( self::SQL_COMPLETE_PARSE_OLD_MEDIA, $this->data + $conditions );
        }

        const SQL_UPDATE_STATISTICS = <<<SQL
    -- SQL_UPDATE_STATISTICS
    UPDATE {{ t(table) }}
      SET likes = ( SELECT sum( likes_count ) FROM {{ t(table_media) }} WHERE public_id = {{ i(public_id) }} ),
          comments = ( SELECT sum( comments_count ) FROM {{ t(table_media) }} WHERE public_id = {{ i(public_id) }} ),
          photos = ( SELECT count( * ) FROM {{ t(table_media) }} WHERE public_id = {{ i(public_id) }} )
      WHERE public_id = {{ i(public_id) }};
SQL;
        public function updateStatistics( $conditions ){
            return $this->db()->query( self::SQL_UPDATE_STATISTICS , $this->data + $conditions );
        }
    }