<?php
    require_once ROOT .'const.php';

    // Load common exceptions
    require_once LIB_DIR .'Exception/Common.php';

    // \Cerceau autoload
    require_once LIB_DIR .'Autoloader.php';

    \Cerceau\Autoloader::register( defined( 'PLATFORM' ) ? PLATFORM : 'dev' );

    \Cerceau\System\Registry::instance()->initialize( \Cerceau\Config\Registry::instance());

    // \PRedis autoload
    require_once EXTERNAL_DIR .'predis/autoload.php';

    date_default_timezone_set( 'Europe/London' );

    // todo temp
    error_reporting( ( E_ALL | E_STRICT ) ^ E_WARNING );