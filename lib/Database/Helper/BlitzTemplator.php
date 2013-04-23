<?php
	namespace Cerceau\Database\Helper;

	class BlitzTemplator implements \Cerceau\Database\I\ISQLTemplator {

        /**
         * @var \Cerceau\Database\I\IConfig
         */
        private $Config;

        /**
         * @param \Cerceau\Database\I\IConfig $Config
         */
        public function __construct( \Cerceau\Database\I\IConfig $Config ){
            $this->Config = $Config;
        }

        /**
         * @param string $tpl
         * @param array $args
         * @param mixed $spotId
         * @return string
         */
        public function parseSQL( $tpl, $args, $spotId = null ){
            if( empty( $args ) && false === strpos( $tpl, '{' ))
                return $tpl;

            $T = new Blitz( $this->Config, $spotId );
            $T->load( $tpl );
            $T->set( $args );

            return $T->parse();
        }
    }
	