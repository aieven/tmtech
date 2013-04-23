<?php
    namespace Cerceau\Model\PostgreSQL\Admin\PeopleAndBrands;

    class Category extends \Cerceau\Model\PostgreSQL\Base {

        const SQL_SELECT_SUBCAT_INFO = <<<SQL
    -- SQL_SELECT_SUBCAT_INFO
    SELECT s.subcat_name, c.cat_name, c.cat_id
      FROM {{ t(partition, "subcategories") }} s
      INNER JOIN {{ t(partition, "categories") }} c
      USING( cat_id )
      WHERE subcat_id = {{ i(subcat_id) }}
SQL;

        /**
         * @param array $conditions
         * @return array
         */
        public function selectSubcatInfo( array $conditions ){
            return $this->db()->selectRecord( self::SQL_SELECT_SUBCAT_INFO, $conditions );
        }

        const SQL_SELECT_CAT_INFO = <<<SQL
    -- SQL_SELECT_CAT_INFO
    SELECT cat_name
      FROM {{ t(partition, "categories") }}
      WHERE cat_id = {{ i(cat_id) }}
SQL;

        /**
         * @param array $conditions
         * @return array
         */
        public function selectCatInfo( array $conditions ){
            return $this->db()->selectRecord( self::SQL_SELECT_CAT_INFO, $conditions );
        }

        const SQL_SELECT_ICON_PATH = <<<SQL
    -- SQL_SELECT_ICONS_PATH
    SELECT subcat_icon
      FROM {{ t(partition, "subcategories") }}
      WHERE subcat_id = {{ i(subcat_id) }};
SQL;

        /**
         * @param array $conditions
         * @return array
         */
        public function selectIconPath( array $conditions ){
            return $this->db()->selectField( self::SQL_SELECT_ICON_PATH, $conditions );
        }

        const SQL_ADD_SUBCATEGORY = <<<SQL
    -- SQL_ADD_SUBCATEGORY
    INSERT INTO {{ t(partition, "subcategories") }}
      ( subcat_name, subcat_icon, cat_id )
      VALUES (
        {{ s(subcat_name) }},
        {{ s(subcat_icon) }},
        {{ i(cat_id) }}
      )
      RETURNING subcat_id, subcat_name, subcat_icon
SQL;

        /**
         * @param array $conditions
         * @return array
         */
        public function addSubcat( array $conditions ){
            return $this->db()->selectRecord( self::SQL_ADD_SUBCATEGORY, $conditions );
        }

        const SQL_RESTORE_SUBCATEGORY = <<<SQL
    -- SQL_RESTORE_SUBCATEGORY
    UPDATE {{ t(partition, "subcategories") }}
      SET deleted = FALSE
      WHERE subcat_name = {{ s(subcat_name) }}
      RETURNING subcat_id, subcat_name, subcat_icon
SQL;

        /**
         * @param array $conditions
         * @return array
         */
        public function restoreSubcat( array $conditions ){
            return $this->db()->selectRecord( self::SQL_ADD_SUBCATEGORY, $conditions );
        }

        const SQL_UPDATE_SUBCATEGORY = <<<SQL
    -- SQL_UPDATE_SUBCATEGORY
    UPDATE {{ t(partition, "subcategories") }}
      SET subcat_name = {{ s(subcat_name) }},
        {{ IF subcat_icon }}
          subcat_icon = {{ s(subcat_icon) }},
        {{ END }}
          cat_id = {{ i(cat_id) }}
      WHERE subcat_id = {{ i(subcat_id) }}
      RETURNING subcat_id, subcat_name, subcat_icon
SQL;

        /**
         * @param array $conditions
         * @return array
         */
        public function updateSubcat( array $conditions ){
            return $this->db()->selectRecord( self::SQL_UPDATE_SUBCATEGORY, $conditions );
        }

        const SQL_DELETE_SUBCATEGORY = <<<SQL
    -- SQL_DELETE_SUBCATEGORY
    UPDATE {{ t(partition, "subcategories") }}
      SET deleted = TRUE
      WHERE subcat_id = {{ i(subcat_id) }};

    -- SQL_DELETE_SUBCATEGORY_PUBLICS
    UPDATE {{ t(partition, "publics") }}
      SET subcat_id = NULL
      WHERE subcat_id = {{ i(subcat_id) }};
SQL;

        /**
         * @param array $conditions
         * @return array
         */
        public function deleteSubcat( array $conditions ){
            return $this->db()->query( self::SQL_DELETE_SUBCATEGORY, $conditions );
        }
    }
