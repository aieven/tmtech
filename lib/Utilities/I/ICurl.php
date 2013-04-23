<?php
namespace Cerceau\Utilities\I;

interface ICurl {

    /**
     * @param array $options
     * @return array
     */
    public function authorizeQuery( $options = array() );

    /**
     * @param string $apiLocation
     * @param string $uri
     * @param array $get
     * @param array $post
     * @return array
     */
    public function apiQuery( $apiLocation , $uri, array $get = array(), array $post = array() );

    /**
     * @param string $apiSendAddress
     * @param array $headers
     * @param String $post
     * @return array
     */
    public function gcmRequest( $apiSendAddress , $headers , $post );

}