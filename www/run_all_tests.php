<?php
    namespace Cerceau;

    define( 'ROOT', dirname(__FILE__) .'/../' );
    define( 'PLATFORM', 'test' );

    require_once ROOT .'init.php';
    require_once EXTERNAL_DIR .'simpletest/autorun.php';

    function getTests( $dir ){
        $DirectoryIterator = new \DirectoryIterator( $dir );
        $paths = array();
        foreach( $DirectoryIterator as $Current ){
            /**
             * @var \DirectoryIterator $Current
             */
            if( $Current->isDot())
                continue;
            if( $Current->isDir()){
                $paths = array_merge( $paths, getTests( $Current->getRealPath()));
            }
            elseif( $Current->isFile()){
                if( $Current->getExtension() === 'php' )
                    $paths[] = $Current->getRealPath();
            }
        }
        return $paths;
    }

    try{
        $paths = getTests( TESTS_DIR );
        foreach( $paths as $path ){
            require_once $path;
        }
    }
    catch(\Exception $e){
        echo '<pre><b>Exception catched:</b> '. $e->getMessage()
            .'<br /><br />In <u>'. $e->getFile()
            .'</u>, at line <b>#'. $e->getLine()
            .'</b><br /><br />'. $e->getTraceAsString() .'</pre>';
    }
