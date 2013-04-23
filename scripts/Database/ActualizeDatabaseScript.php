<?php
    namespace Cerceau\Script\Database;

    abstract class ActualizeDatabaseScript extends \Cerceau\Script\Base {

        protected $version;
        protected $dbName;

        /**
         * @var \Cerceau\Database\I\IDatabase $Database
         */
        protected $Database;

        /**
         * @var \Cerceau\Database\I\IConfig $Config
         */
        protected $Config;

        public function run(){
            $this->version = intval( file_get_contents( SQL_DIR .'VERSION' ));
            $this->Database = $this->db( $this->dbName );
            $this->Config = \Cerceau\Config\Database::instance( $this->dbName );
            $this->processDb();
//            $this->backup();
        }

        protected function getBackupName(){
            return SQL_DIR .'backups/v'. $this->version .'__'. $this->dbName .'.sql';
        }

        protected function getDbName(){
            return $this->Config->get( 'dbname' );
        }

        protected function backup(){
            if( PLATFORM === 'dev' ){
                exec(
                    'PGPASSWORD='. $this->Config->get( 'password' )
                        .' && export PGPASSWORD && pg_dump -U '. $this->Config->get( 'user' )
                        .' -f '. $this->getBackupName() .' '. $this->getDbName()
                        .' && unset PGPASSWORD'
                );
            }
        }

        protected function processDb(){
            $version = $this->Database->selectField( <<<SQL
    -- SQL_GET_VERSION
    SELECT version FROM version
SQL
            );
            if(!$version ){
                $this->initializeDb();
                $version = 0;
            }
            else
                $version = intval( reset( $version ));

            while( $version < $this->version ){
                $version++;
                $this->updateDb( $version );
            }
        }

        protected function initializeDb(){
            $sql = file_get_contents( SQL_DIR . $this->dbName .'.sql' );
            if(!$sql )
                throw new \Exception( 'No initial sql for database "'. $this->dbName .'"' );
            if(!$this->Database->query( <<<SQL
    -- SQL_INITIALIZE_DATABASE
    CREATE TABLE IF NOT EXISTS version ( version integer PRIMARY KEY );
    INSERT INTO version VALUES ( 0 );

    $sql;
SQL
            ))
                throw new \Exception( 'SQL error!' );
        }

        protected function updateDb( $version ){
            $filename = SQL_DIR . $this->dbName .'_'. $version .'.sql';
            $sql = '';
            if( file_exists( $filename ))
                $sql = file_get_contents( $filename );
            if(!$this->Database->query( <<<SQL
    -- SQL_UPDATE_DATABASE
    UPDATE version SET version = $version;

    $sql;
SQL
            ))
                throw new \Exception( 'SQL error!' );
        }
    }
