<?php
    namespace Cerceau;

    define( 'ROOT', dirname(__FILE__) .'/../' );
    define( 'PLATFORM', 'dev' );

    try {
        require_once ROOT .'init.php';

        // caching routes
        \Cerceau\System\Router::instance()->cacheRoutes();

        // compiling JS locale files
        \Cerceau\Utilities\I18n::compile();

        // bootstrap JS and CSS
        $BootstrapScript = new \Cerceau\Script\BootstrapScript();
        $BootstrapScript->run();
    }
    catch (\Exception $e){
        echo '<pre><b>Exception catched:</b> '. $e->getMessage()
            .'<br /><br />In <u>'. $e->getFile()
            .'</u>, at line <b>#'. $e->getLine()
            .'</b><br /><br />'. $e->getTraceAsString() .'</pre>';
    }

    echo '<div style="padding: 8px; margin-top: 1em; background-color: green; color: white;">Done.</div>';
