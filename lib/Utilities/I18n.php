<?php
    namespace Cerceau\Utilities;

    class I18n implements I\II18n {
        /**
         * @var array
         */
        protected static $Instances;

        /**
         * @var I18n
         */
        protected $locale;

        protected $cache = array();

        protected function __construct( $locale ){
            $this->locale =  $locale;
        }

        /**
         * @static
         * @param string $locale
         * @return I18n
         */
        public static function instance( $locale ){
            if(!isset( static::$Instances[$locale] )){
                static::$Instances[$locale] = new static( $locale );
            }
            return static::$Instances[$locale];
        }

        /**
         * @return string
         * @throws \InvalidArgumentException
         * @throws \UnexpectedValueException
         */
        public function get(){
            $args = func_get_args();
            if(!count( $args ))
                throw new \InvalidArgumentException( __CLASS__.'::'.__METHOD__.' have to pass at least 1 argument: locale variable name ' );
            $string = array_shift( $args );
            $value = $this->_pick( explode( '.', $string ));
            if(!is_string( $value ))
                throw new \UnexpectedValueException( 'Locale variable '. $string .' does not exist' );
            return vsprintf( $value, $args );
        }

        /**
         * @return string
         */
        public function pick(){
            return $this->_pick( func_get_args());
        }

        public static function compile(){
            $DirectoryIterator = new \DirectoryIterator( LOCALES_DIR );
            /**
             * @var \DirectoryIterator $Dir
             * @var \DirectoryIterator $File
             */
            foreach( $DirectoryIterator as $Dir ){
                if( $Dir->isDot())
                    continue;

                if( $Dir->isDir()){
                    $locale = $Dir->getFilename();
                    $Instance = self::instance( $locale );

                    $File = new \SplFileObject( LOCALES_JS_DIR . $locale .'.js', 'w' );
                    $File->fwrite( 'I18n.set('. $Instance->jsonLists() .');' );
                }
            }
        }

        public function jsonLists(){
            /**
             * @var \DirectoryIterator $File
             */
            $DirectoryIterator = new \DirectoryIterator( LOCALES_DIR . $this->locale );
            foreach( $DirectoryIterator as $File ){
                if( $File->isFile())
                    $this->pick( $File->getBasename('.php'));
            }
            return json_encode( $this->cache );
        }

        protected function _pick( $args ){
            if(!count( $args ))
                throw new \InvalidArgumentException( __CLASS__.'::'.__METHOD__.' have to pass at least 1 argument: loclist name ' );
            $locListName = array_shift( $args );
            if(!isset( $this->cache[$locListName] )){
                include LOCALES_DIR . $this->locale .'/'. $locListName .'.php';
                $this->cache[$locListName] = $list;
            }
            $value = $this->cache[$locListName];
            $names = array();
            while( $sub = array_shift( $args )){
                $names[] = $sub;
                if(!isset( $value[$sub] ))
                    throw new \UnexpectedValueException( 'Locale value for '. implode( '.', $names ) .' does not exist in loclist "'. $locListName .'"' );
                $value = $value[$sub];
            }
            return $value;
        }
    }