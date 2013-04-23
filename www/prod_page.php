<?php
    namespace Cerceau\System;

    define( 'ROOT', dirname(__FILE__) .'/../' );
    define( 'PLATFORM', isset( $_SERVER['PLATFORM'] ) ? $_SERVER['PLATFORM'] : 'prod' );

    try{
        require_once ROOT .'init.php';

        echo Registry::instance()->Router()->getController()->run();
    }
    catch( \UnexpectedValueException $E ){
        Registry::instance()->Logger()->log( 'fatal-error', $E->getMessage());
        header( 'Internal Server Error', true, 500 );
        exit;
    }
    catch( \Exception $E ){
        Registry::instance()->Logger()->logException( $E );
        header( 'Internal Server Error', true, 500 );
        exit;
    }
