<?php
    namespace Cerceau\Model\PostgreSQL\Admin\PeopleAndBrands;

    class Catalog extends \Cerceau\Model\PostgreSQL\Base {

        const SQL_SELECT_CATEGORIES = <<<SQL
    -- SQL_SELECT_CATEGORIES
    SELECT *
      FROM {{ t(partition, "categories") }}
      ORDER BY cat_name DESC
SQL;

        /**
         * @param array $conditions
         * @return array
         */
        public function selectCategories( array $conditions ){
            return $this->db()->selectTable( self::SQL_SELECT_CATEGORIES, $conditions );
        }

        const SQL_SELECT_SUBCATEGORIES = <<<SQL
    -- SQL_SELECT_SUBCATEGORIES
    SELECT *
      FROM {{ t(partition, "subcategories") }}
      WHERE deleted = FALSE
      ORDER BY subcat_name
SQL;

        /**
         * @param array $conditions
         * @return array
         */
        public function selectSubcategories( array $conditions ){
            return $this->db()->selectTable( self::SQL_SELECT_SUBCATEGORIES, $conditions );
        }

        const SQL_SELECT_SUBCATEGORIES_PUBLICS_COUNT = <<<SQL
    -- SQL_SELECT_SUBCATEGORIES_PUBLICS_COUNT
    SELECT count(*) as public_count
      FROM {{ t(partition, "publics") }}
      WHERE subcat_id = {{ i(subcat_id) }}
        AND deleted = FALSE
SQL;

        /**
         * @param array $conditions
         * @return array
         */
        public function selectSubcatPublicsCount( array $conditions ){
            return $this->db()->selectField( self::SQL_SELECT_SUBCATEGORIES_PUBLICS_COUNT, $conditions );
        }

        const SQL_SELECT_CATEGORIES_PUBLICS_COUNT = <<<SQL
    -- SQL_SELECT_CATEGORIES_PUBLICS_COUNT
    SELECT count(*) as public_count
      FROM {{ t(partition, "publics") }}
      WHERE cat_id = {{ i(cat_id) }}
        AND deleted = FALSE
SQL;

        /**
         * @param array $conditions
         * @return array
         */
        public function selectCatPublicsCount( array $conditions ){
            return $this->db()->selectField( self::SQL_SELECT_CATEGORIES_PUBLICS_COUNT, $conditions );
        }

        const SQL_SELECT_BRANDS_PUBLICS = <<<SQL
    -- SQL_SELECT_BRANDS_PUBLICS
    SELECT *
      FROM {{ t(partition, "publics") }}
      WHERE deleted = FALSE
      {{ IF cat_id }}
        AND cat_id = {{ i(cat_id) }}
      {{ END }}
      {{ IF subcat_id }}
        AND subcat_id = {{ i(subcat_id) }}
      {{ END }}
      {{ IF is_api }}
        AND instagram_id IS NOT NULL
      {{ END }}
      {{ IF full_name }}
        AND full_name ILIKE {{ s(full_name) }}
      {{ END }}

      ORDER BY full_name
SQL;
        const SQL_SELECT_PEOPLE_PUBLICS = <<<SQL
    -- SQL_SELECT_PEOPLE_PUBLICS
          SELECT  public_id, cat_id, subcat_id, instagram_id, username, profile_picture,
                  full_name, followers, photos, likes, comments
      FROM {{ t("people_publics") }}
      WHERE deleted = FALSE
      {{ IF cat_id }}
        AND cat_id = {{ i(cat_id) }}
      {{ END }}
      {{ IF subcat_id }}
        AND subcat_id = {{ i(subcat_id) }}
      {{ END }}
      {{ IF instagram_id }}
        AND instagram_id = {{ i(instagram_id) }}
      {{ END }}
      {{ IF is_api }}
        AND instagram_id IS NOT NULL
      {{ END }}
      {{ IF full_name }}
        AND full_name ILIKE {{ s(full_name) }}
      {{ END }}

      GROUP BY public_id
      {{ IF order_by }}
      ORDER BY {{ order_by }} DESC
      {{ END }}
      {{ UNLESS order_by }}
      ORDER BY full_name
      {{ END }}
SQL;
        /**
         * @param array $conditions
         * @return array
         */
        public function selectPublics( array $conditions ){
            $sql = $conditions['partition'] === 'brands'
                ? self::SQL_SELECT_BRANDS_PUBLICS
                : self::SQL_SELECT_PEOPLE_PUBLICS;
            return $this->db()->selectTable( $sql, $conditions );
        }

        const SQL_SELECT_CHARTS = <<<SQL
    -- SQL_SELECT_CHARTS
    SELECT  public_id, instagram_id, username, profile_picture, full_name, followers, likes, comments, photos
      FROM {{ t("people_publics") }}
      WHERE deleted = FALSE
        AND instagram_id IS NOT NULL
      GROUP BY public_id
      ORDER BY {{ order_by }} DESC
SQL;

        /**
         * @param array $conditions
         * @return array
         */
        public function selectCharts( array $conditions ){
            return $this->db()->selectTable( self::SQL_SELECT_CHARTS, $conditions );
        }
    }
