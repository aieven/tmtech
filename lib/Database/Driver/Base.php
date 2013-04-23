<?php
    namespace Cerceau\Database\Driver;

	abstract class Base implements \Cerceau\Database\I\IDriver {

        /**
         * @param mixed $value
         * @return string
         */
        public function toScalar( $value ){
            // any other value can be just escaped
            if( is_null( $value ))
                return null;

            return '\''. $this->escapeString( $value ) .'\'';
        }

        /**
         * @param $param
         * @param $value
         * @return string
         */
        public function equalExpression( $param, $value ){
            $value = $this->toScalar( $value );
            if( is_null( $value ))
                return null;
            else
                return $param .' = '. $value;
        }
    }
	