<?php
    namespace Cerceau\Utilities\I;

    interface ISpecialConfig extends \ArrayAccess {
        public function get( $offset );
        public function set( $offset, $value );
    }