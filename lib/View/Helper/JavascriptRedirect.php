<?php
    namespace Cerceau\View\Helper;

    class JavascriptRedirect {

        public static function set( $url = null, $timeout = 2 ){
            if( $url )
                $url = '"'. $url .'"';
            else
                $url = '"/" + Globals.get( "goto" )';

            \Cerceau\View\Native::appendToTrunk( 'footer', '
<script type="text/javascript">
$(document).ready(function(){
    define( "Campaign.Created", [ "Globals", "Router" ], function( Globals, Router ){
        return setTimeout(function(){
            Router.reload( '. $url .' );
        }, '. ( $timeout * 1000 ) .');
    });
});
</script>'
            );
        }
    }