<?php
    namespace Cerceau;

    if(!isset($_GET['test']))
        throw new \ErrorException( 'Test not specified' );

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
        if( is_dir( TESTS_DIR . $_GET['test'] )){
            $paths = getTests( TESTS_DIR . $_GET['test'] );
            foreach( $paths as $path ){
                require_once $path;
            }
        }
        else{
            $testFile = $_GET['test'] .'TestCase.php';

            if(!file_exists( TESTS_DIR . $testFile ))
                throw new \ErrorException( 'Test file not exists' );

            require_once TESTS_DIR . $testFile;
        }
    }
    catch(\Exception $e){
        echo '<pre><b>Exception catched:</b> '. $e->getMessage()
            .'<br /><br />In <u>'. $e->getFile()
            .'</u>, at line <b>#'. $e->getLine()
            .'</b><br /><br />'. $e->getTraceAsString() .'</pre>';
    }

