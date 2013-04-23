<?php
    namespace Cerceau\System;

    define( 'ROOT', dirname(__FILE__) .'/../' );
    define( 'PLATFORM', isset( $_SERVER['PLATFORM'] ) ? $_SERVER['PLATFORM'] : 'dev' );

    try{
        require_once ROOT .'init.php';

        echo Registry::instance()->Router()->getController()->run();
    }
    catch( \Exception $E ){
        echo '<pre><b>Exception catched:</b> '. $E->getMessage()
            .'<br /><br />In <u>'. $E->getFile()
            .'</u>, at line <b>#'. $E->getLine()
            .'</b><br /><br />'. $E->getTraceAsString() .'</pre>';
    }
