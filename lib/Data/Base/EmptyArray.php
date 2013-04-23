<?php
    namespace Cerceau\Data\Base;

    class EmptyArray implements \ArrayAccess {

        final public function offsetExists( $offset ){
            return true;
        }

        final public function offsetGet( $offset ){
            return new EmptyArray();
        }

        final public function offsetSet( $offset, $value ){}

        final public function offsetUnset( $offset ){}

        final public function __toString(){
            return null;
        }
    }
