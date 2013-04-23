<?php
    namespace Cerceau\Data\Base\Field;

	class FieldString extends FieldScalar {
        private $allowableTags = array();

        /**
         * @param $value
         * @param bool $fetch
         * @return float|null
         */
        public function set( $value, $fetch = true ){
            if( $fetch && $this->readonly )
                return null;

            if( $this->const ){
                $oldValue = $this->get();
                if( null !== $oldValue )
                    return $oldValue;
            }

            if( is_scalar( $value )){
                if( !$this->allowableTags )
                    return $this->setValue( htmlspecialchars( $value , ENT_QUOTES ) );
                else{
                    $value = htmlspecialchars( $value , ENT_QUOTES );
                    foreach( $this->allowableTags as $tag )
                        $value = str_replace ( htmlspecialchars($tag) , $tag , $value );
                    return $this->setValue( $value );
                }
            }

            $this->setValue( null );
            return null;

        }

        protected function validateFixedLength( $len ){
            $value = $this->get();
            if( null === $value )
                return true;
            return $len === mb_strlen( $value );
        }

        protected function validateMatch( $preg ){
            $value = $this->get();
            if( null === $value )
                return true;
            return preg_match( '/'. $preg .'/', $value );
        }

        protected function validateEmail(){
            $value = $this->get();
            if( null === $value )
                return true;
            return !!filter_var( $value, FILTER_VALIDATE_EMAIL );
        }

        protected function validateUrl(){
            $value = $this->get();
            if( null === $value )
                return true;
            return !!filter_var( $value, FILTER_VALIDATE_URL );
        }

        /**
         * @param array|string $tags
         * @return void
         */
        public function setAllowableTags( $tags ){
            $this->allowableTags = $tags;
        }
    }
