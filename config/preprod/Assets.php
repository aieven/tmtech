<?php
    namespace Cerceau\Config;

    class Assets extends \Cerceau\View\Config {
        protected static $css = array(
            'bootstrap.min',
            'datepicker',
            'jcrop.min',
            'main',
        );
        protected static $js = array(
            'compiled'
        );
    }
