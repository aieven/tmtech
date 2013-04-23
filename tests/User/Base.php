<?php
    namespace Cerceau\Test\User;

    abstract class Base extends \Cerceau\Test\Base {

        /**
         * @var \Cerceau\Database\I\IDatabase
         */
        protected $Database;

        /**
         * @var \PRedis\Client
         */
        protected $Redis;

        public function setUp(){
            $this->Database = $this->db( 'main' );
            $this->Database->query( <<<SQL
    -- SQL_SETUP_DATABASE_FACTORY_TEST_1
    TRUNCATE {{ t("admins") }};
    TRUNCATE {{ t("bots") }};
    TRUNCATE {{ t("media") }};
    TRUNCATE {{ t("people_categories") }};
    TRUNCATE {{ t("people_subcategories") }};
    TRUNCATE {{ t("people_publics") }};
    TRUNCATE {{ t("brands_categories") }};
    TRUNCATE {{ t("brands_subcategories") }};
    TRUNCATE {{ t("brands_publics") }};
    TRUNCATE {{ t("snapshots") }};
    TRUNCATE {{ t("gal_categories") }};
    TRUNCATE {{ t("gallery_publics") }};

     ALTER SEQUENCE gallery_publics_public_id_seq RESTART;
SQL
            );
            $this->Redis = \Cerceau\NoSQL\Redis::instance()->get();
            $this->Redis->flushdb();
        }
    }
