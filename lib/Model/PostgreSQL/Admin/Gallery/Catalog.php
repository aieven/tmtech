<?php
    namespace Cerceau\Model\PostgreSQL\Admin\Gallery;

    class Catalog extends \Cerceau\Model\PostgreSQL\Base {

        const SQL_SELECT_GALLERY_CATEGORIES = <<<SQL
    -- SQL_SELECT_GALLERY_CATEGORIES
    SELECT *
      FROM {{ t("gal_categories") }}
      WHERE deleted = 0
      {{ IF published }}
      AND published = {{ i(published) }}
      {{ END }}
      ORDER BY order_id
SQL;
        public function selectGalleryCategories( $conditions = array()){
            return $this->db()->selectTable( self::SQL_SELECT_GALLERY_CATEGORIES, $conditions );
        }

        const SQL_DELETE_CATEGORY = <<<SQL
    -- SQL_DELETE_CATEGORY
    UPDATE
    {{ t("gal_categories") }}
    SET deleted = 1
      WHERE gallery_id = {{ i(gallery_id) }}
SQL;
        public function deleteCategory( $conditions = array()){
            return $this->db()->query( self::SQL_DELETE_CATEGORY, $conditions );
        }

        const SQL_PUBLIC_CATEGORY = <<<SQL
    -- SQL_PUBLIC_CATEGORY
    UPDATE
    {{ t("gal_categories") }}
    SET published = {{ i(published) }}
      WHERE gallery_id = {{ i(gallery_id) }}
SQL;
        public function publicCategory( $conditions = array()){
            return $this->db()->query( self::SQL_PUBLIC_CATEGORY, $conditions );
        }

        const SQL_REORDER_CATEGORIES = <<<SQL
    -- SQL_REORDER_CATEGORIES
    BEGIN;
      {{ BEGIN ids }}
      UPDATE {{ t("gal_categories") }} SET order_id = {{ i(position) }} WHERE gallery_id = {{ i(gallery_id) }};
      {{ END }}
    COMMIT;
SQL;
        public function reorder( $ids ){
            return $this->db()->query( self::SQL_REORDER_CATEGORIES, array( 'ids' => $ids ));
        }

    }