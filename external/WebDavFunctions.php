<?php
    # dummy

    /**
     * @param $host
     * @param $user
     * @param $password
     * @return resource
     */
    function webdav_connect( $host, $user, $password ){}

    /**
     * @param $file
     * @param $data
     * @return bool
     */
    function webdav_put( $file, $data ){}

    /**
     * @param $from
     * @param $to
     * @param $data
     * @return bool
     */
    function webdav_copy( $from, $to, $data ){}

    function webdav_mkcol( $file, $data ){}
    function webdav_delete( $file ){}

    function webdav_close(){}