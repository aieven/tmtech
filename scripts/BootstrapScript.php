<?php
    namespace Cerceau\Script;

    use \Cerceau\View\Config;

    class BootstrapScript extends \Cerceau\Script\Base {

        public function run(){
            /**
             * JS concatenate and uglify
             */
            $jsFrom = \Cerceau\Config\Assets::getJsFiles();
            $jsCompiled = ROOT_WWW . JS_DIR .'compiled.js';
            $File = new \SplFileObject( $jsCompiled, 'w' );
            foreach( $jsFrom as $js ){
                $path = ROOT_WWW . JS_DIR . $js .'.js';
                $File->fwrite( file_get_contents( $path ) ."\n");
            }
            exec( 'uglifyjs --verbose --no-copyright --overwrite '. $jsCompiled );
        }
    }
