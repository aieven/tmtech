<?php
    namespace Cerceau;

    if(!isset( $_SERVER['argv'][1] ))
        throw new \ErrorException( 'Script not specified' );

    $script = str_replace( '/', '\\', $_SERVER['argv'][1] );
    $scriptClass = '\\Cerceau\\Script\\'. $script .'Script';

    define( 'ROOT', dirname(__FILE__) .'/' );
    define( 'PLATFORM', 'prod' );

    require_once ROOT .'init.php';

    if(!class_exists( $scriptClass ))
        throw new \ErrorException( 'Script not exists "'. $script .'"' );

    $argv = array_slice( $_SERVER['argv'], 2 );

    /**
     * @var \Cerceau\Script\Base $Script
     */
    $Script = new $scriptClass( $argv );
    $Script->run();

    echo "\n\n";