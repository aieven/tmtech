<?php
    namespace Cerceau\View;

	class Config {
        protected static
            $css = array(),
            $less = array(),
            $js = array();

        /**
         * @static
         * @param string|null $theme
         * @return string
         */
        public static function css( $theme = null ){
            $html = '';
            $dir = CSS_DIR . ( $theme ? $theme .'/' : '' );
            foreach( static::$css as $css ){
                $path = $dir . $css .'.css';
                $time = filemtime( ROOT_WWW .$path );
                if( $time )
                    $html .= '<link media="screen" href="/'. $path .'?'. $time .'" type="text/css" rel="stylesheet" />';
            }
            return $html;
        }

        /**
         * @static
         * @param string|null $theme
         * @return string
         */
        public static function less( $theme = null ){
            $html = '';
            $dir = LESS_DIR . ( $theme ? $theme .'/' : '' );
            foreach( static::$less as $less ){
                $path = $dir . $less .'.less';
                $html .= '<link rel="stylesheet/less" href="/'. $path .'?'. time() .'" />';
            }
            return $html;
        }

        /**
         * @static
         * @return string
         */
        public static function js(){
            $html = '';
            foreach( static::$js as $js ){
                $path = JS_DIR . $js .'.js';
                $time = filemtime( ROOT_WWW . $path );
                if( $time )
                    $html .= '<script type="text/javascript" src="/'. $path .'?'. $time .'"></script>';
            }
            return $html;
        }

        /**
         * @static
         * @return array
         */
        public static function getJsFiles(){
            return static::$js;
        }

        /**
         * @static
         * @param $globals
         * @return string
         */
        public static function globals( $globals ){
            $html = '<script type="text/javascript">window.GLOBALS = '. json_encode( $globals ) .';</script>';
            return $html;
        }
	}