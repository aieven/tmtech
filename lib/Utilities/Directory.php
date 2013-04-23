<?php
    namespace Cerceau\Utilities;

    class Directory {
        /**
         * @var Directory
         */
        protected static $Instance;

        protected function __construct(){}

        /**
         * @static
         * @return Directory
         */
        public static function instance(){
            if(!static::$Instance ){
                static::$Instance = new static();
            }
            return static::$Instance;
        }

        /**
         * @param $path
         * @param int $chmod
         * @return bool
         */
        public function createRecursive( $path, $chmod = 0775 ){
            $path = rtrim( $path, '/' );
            if( is_dir( $path ))
                return true;

            try {
                $parent = preg_replace( '#/\w+$#', '', $path );
                if( $parent !== $path && $this->createRecursive( $parent, $chmod )){
                    mkdir( $path, $chmod );
                    chmod( $path, $chmod );
                    return true;
                }
            }
            catch( \Exception $E ){
            }
            return false;
        }
    }