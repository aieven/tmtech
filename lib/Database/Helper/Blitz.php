<?php
    namespace Cerceau\Database\Helper;

    class Blitz extends \Blitz {

        /**
         * @var \Cerceau\Database\I\IConfig $Config
         */
        private $Config;

        private $spotId;

        /**
         * @param \Cerceau\Database\I\IConfig $Config
         * @param $spotId
         */
        public function __construct( \Cerceau\Database\I\IConfig $Config, $spotId = null ){
            $this->Config = $Config;
            $this->spotId = $spotId;
            parent::__construct();
        }

        public function t( $table, $spotId = null ){
            if(!$spotId )
                $spotId = $this->spotId;
            return $this->Config->getTablesConfig()->getTable( $table ) . ( $spotId ? '_'. $spotId : '' );
        }

        public function spot( $spotId = null ){
            if(!$spotId )
                $spotId = $this->spotId;
            return $spotId ? : '';
        }

        public function date( $arg ){
            return date( 'Y-m-d', $arg );
        }

        public function i( $arg ){
            return intval( $arg );
        }

        public function f( $arg ){
            return floatval( $arg );
        }

        public function s( $arg ){
            return '\''. $this->Config->getDriver()->escapeString( strval( $arg )) .'\'';
        }

        public function ia( $args, $brackets = '()' ){
            return $brackets[0] . implode( ',', array_map( 'intval', $args )) . $brackets[1];
        }

        public function fa( $args, $brackets = '()' ){
            return $brackets[0] . implode( ',', array_map( 'floatval', $args )) . $brackets[1];
        }

        public function sa( $args, $brackets = '()' ){
            if( is_array( $args )){
                foreach( $args as &$arg )
                    $arg = $this->s( $arg );
                $val = implode( ',', $args );
            }
            else {
                $val = $this->s( $args );
            }
            return $brackets[0] . $val . $brackets[1];
        }

        public function values( $args, $brackets = '()' ){
            foreach( $args as &$arg )
                $arg = $this->sa( $arg );
            return $brackets[0] . implode( ',', $args ) . $brackets[1];
        }

        public function conditions( $args, $connectBy = 'AND' ){
            return implode( ' '. $connectBy .' ', $args );
        }

    }
