<?php
	namespace Cerceau\Api;

	class Blitz extends \Blitz {

        private
            $templates_path,
            $templates_ext;

        /**
         * @param $tpl
         * @param $path
         * @param $ext
         */
        public function __construct( $tpl, $path, $ext ){
            $this->templates_path = $path;
            $this->templates_ext = $ext;
            parent::__construct( $path . $tpl . $ext );
        }

        /**
         * @param $tpl
         * @param array $globals
         * @return string
         */
        public function inject( $tpl, array $globals = array() ){
            return $this->include( $this->templates_path . $tpl . $this->templates_ext, $globals );
        }

        /**
         * @param $data
         * @return string
         */
        public function toJson( $data ){
            return json_encode( $data );
        }

        /**
         * @param $one
         * @param $two
         * @param $value
         * @param $value2
         * @return mixed
         */
        public function ifEqual( $one, $two, $value, $value2 ){
            return $one === $two ? $value : $value2;
        }
    }
	