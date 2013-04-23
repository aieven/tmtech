<?php
	namespace Cerceau\View;

	class Native extends \Cerceau\View\HtmlBase {
        const TEMPLATE_EXT = '.phtml';

        private static $trunk = array();

        public static function appendToTrunk( $key, $val ){
            if(!isset( static::$trunk[$key] ))
                static::$trunk[$key] = array();

            static::$trunk[$key][] = $val;
        }

        public static function getFromTrunk( $key ){
            return isset( static::$trunk[$key] ) ? static::$trunk[$key] : array();
        }

        public function html(){
            $d = $this->data;
            $p = $this->page;
            $globals = $this->pathLayout ? $this->globals : $this->json;
            $theme = $this->theme;
            $closure = function( $path, $yield = '' ) use( $d, $p, $globals, $theme ){
                /**
                 * shortcuts
                 */
                $Url = \Cerceau\System\Registry::instance()->Url();
                $I18n = \Cerceau\System\Registry::instance()->I18n();

                $inject = function( $template ) use( $theme ){
                    $path = ROOT . Native::THEME_DIR . $theme . Native::TEMPLATE_DIR . $template . Native::TEMPLATE_EXT;
                    if( file_exists( $path ) )
                        return $path;

                    $path = ROOT . Native::THEME_DIR . Native::DEFAULT_THEME . Native::TEMPLATE_DIR . $template . Native::TEMPLATE_EXT;
                    if( file_exists( $path ) )
                        return $path;

                    return ROOT . Native::THEME_DIR . Native::DEFAULT_THEME . Native::TEMPLATE_DIR . Native::DEFAULT_TEMPLATE . Native::TEMPLATE_EXT;
                };

                $append = function( $key, $val ) use( $theme ){
                    Native::appendToTrunk( $key, $val );
                };
                $get = function( $key ) use( $theme ){
                    return Native::getFromTrunk( $key );
                };

                ob_start();
                try {
                    include $path;
                    $result = ob_get_clean();
                }
                catch( \Exception $e ){
                    ob_end_clean();
                    throw $e;
                }
                return $result;
            };
            $yield = $closure( $this->pathTemplate . $this->template . self::TEMPLATE_EXT );

            if( $this->pathLayout )
                $yield = $closure( $this->pathLayout . $this->layout . self::TEMPLATE_EXT, $yield );

            return $yield;
        }
    }
	