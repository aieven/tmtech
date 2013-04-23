<?php
    namespace Cerceau\Resource;

    class Webdav {

        public function putFiles( $url, array $files ){
            $res = \webdav_connect( 'http://'. $url .'/', 'root', 'root' );
            if(!$res )
                return false;
            foreach( $files as $file => $data ){
                if(!\webdav_put( $file, $data, $res ))
                    return false;
            }
            \webdav_close( $res );
            return true;
        }

        public function deleteFiles( $url, array $files ){
            $res = \webdav_connect( 'http://'. $url .'/', 'root', 'root' );
            if( $res )
                foreach( $files as $file )
                    \webdav_delete( $file, $res );
            \webdav_close( $res );
            return true;
        }
    }