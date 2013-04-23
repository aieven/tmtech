<?php
	namespace Cerceau\View;

	class Redirect extends Json {
        public function render(){
            if(!$this->data['location'] )
                throw new \UnexpectedValueException( 'Location for redirect is not set' );

            $location = $this->data['location'];
            unset( $this->data['location'] );

            $params = array();
            foreach( $this->data as $k => $v )
                $params[] = $k .'='. urlencode( $v );

            $header = 'Location: '. $location . implode( '&', $params );

            if( PLATFORM === 'test' )
                return $header;

            header( $header );
            exit;
        }
	}
	