<?php
    namespace Cerceau;

    if(!isset( $_SERVER['SCRIPT'] ))
        throw new \ErrorException( 'Script not specified' );

    $script = str_replace( '/', '\\', $_SERVER['SCRIPT'] );
    $scriptClass = '\\Cerceau\\Script\\'. $script .'Script';

    define( 'ROOT', dirname(__FILE__) .'/' );
    define( 'PLATFORM', isset( $_SERVER['PLATFORM'] ) ? $_SERVER['PLATFORM'] : 'dev' );

    echo "\n";
    try{
        require_once ROOT .'init.php';

        if(!class_exists( $scriptClass ))
            throw new \ErrorException( 'Script not exists "'. $script .'"' );

        $argv = array_slice( $_SERVER['argv'], 1 );

        /**
         * @var \Cerceau\Script\Base $Script
         */
        $Script = new $scriptClass( $argv );
        $Script->run();

        echo 'Done';
    }
    catch( \Exception $E ){
        echo $E->getMessage() ."\n\n"
            .'In '. $E->getFile()
            .', at line #'. $E->getLine() ."\n\n"
            . $E->getTraceAsString();
    }
    echo "\n\n";
