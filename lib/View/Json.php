<?php
	namespace Cerceau\View;

	class Json implements \Cerceau\I\IView {
        protected $data = array();

        public function set( $name, $value = null ){
            if( is_array( $name ))
                $this->data += $name;
            else
                $this->data[$name] = $value;
        }

        public function render(){
            \Cerceau\System\Registry::instance()->Response()->header( 'Content-type: application/json; charset=UTF-8' );

            return json_encode( $this->data );
        }
	}
	