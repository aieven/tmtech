<?php
    namespace Cerceau;

    if(!isset($_GET['script']))
        throw new \ErrorException( 'Script not specified' );

    $script = str_replace( '/', '\\', $_GET['script'] );
    $scriptClass = '\\Cerceau\\Script\\'. $script .'Script';

    define( 'ROOT', dirname(__FILE__) .'/../' );
    define( 'PLATFORM', isset($_GET['platform']) ? $_GET['platform'] : 'dev' );

    try{
        require_once ROOT .'init.php';

        if(!class_exists( $scriptClass ))
            throw new \ErrorException( 'Script not exists "'. $script .'"' );

        /**
         * @var \Cerceau\Script\Base $Script
         */
        $Script = new $scriptClass( isset( $_GET['arg'] ) ? array( $_GET['arg'] ) : array());
        $Script->run( true ); // queues run once

        echo '<div style="padding: 8px; margin-top: 1em; background-color: green; color: white;">Done.</div>';
    }
    catch( \Exception $E ){
        echo '<div style="padding: 8px; margin-top: 1em; background-color: red; color: white;">Exception catched:</div>'
            .'<pre>'. $E->getMessage()
            .'<br /><br />In <u>'. $E->getFile()
            .'</u>, at line <b>#'. $E->getLine()
            .'</b><br /><br />'. $E->getTraceAsString() .'</pre>';
    }
