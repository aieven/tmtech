<?php
    namespace Cerceau\Data\Base\Field;

    class FieldSet extends FieldEnum {

        /**
         * @param $value
         * @param bool $fetch
         * @return mixed
         * @throws \UnexpectedValueException
         */
        public function set( $value, $fetch = true ){
            if( $fetch && $this->readonly )
                return null;

            if( $this->const ){
                $oldValue = $this->get();
                if( null !== $oldValue )
                    return $oldValue;
            }

            if( is_null( $value ))
                return $this->setValue( null );

            // from scalar
            if( is_string( $value ))
                $value = explode( ',', trim( $value, '{}' ));

            if(!is_array( $value ))
                throw new \UnexpectedValueException( 'Wrong data type for field "'. $this->offset .'"' );

            foreach( $value as $k => &$v ){
                $v = trim( $v, '"' );
                if( isset( $this->types[$v] )){
                    if( is_numeric( $v ))
                        $v = intval( $v );
                    else{
                        if( preg_match( '#\W#', $v ))
                            throw new \UnexpectedValueException( 'Key of set "'. $v .'" for field "'. $this->offset .'" is illegal' );
                    }
                }else{
                    unset( $value[$k] );
                }
            }

            return $this->setValue( $value );
        }

        /**
         * @return array
         */
        public function get(){
            $a = $this->Storage->export();
            return $a[$this->offset];
        }

        /**
         * @return array
         */
        public function toScalar(){
            $a = $this->get();
            if( is_null( $a ))
                return null;

            foreach( $a as &$v ){
                if(!is_numeric( $v ))
                    $v = '"'. $v .'"';
            }
            return '{'. implode( ',', $a ) .'}';
        }
    }
