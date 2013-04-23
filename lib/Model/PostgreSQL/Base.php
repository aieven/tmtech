<?php
    namespace Cerceau\Model\PostgreSQL;

	class Base extends \Cerceau\Model\Base {

        protected static $SQL_BASE_CREATE = <<<SQL
    -- SQL_BASE_CREATE {{ t( table, spot ) }}
    INSERT INTO {{ t( table, spot ) }}
      ( {{ keys }} ) VALUES
      ( {{ vals }} )
      RETURNING *
SQL;
    }
