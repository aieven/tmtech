<?php
    namespace Cerceau\Resource;

    class Manager {

        const
            MAX_SPOTS_BY_UPSTREAM = 100,
            MAX_SPOTS_BY_BACKEND = 15,

            NGINX_CONFIGS_TPLS = 'config/nginx/',
            NGINX_UPSTREAM_TPL = 'resources_upstream.tpl',
            NGINX_BACKEND_TPL = 'resources_backend.tpl',

            BACKENDS_CONFIG_NAME = 'backends',
            SPOTS_CONFIG_NAME = 'spots'
        ;

        /**
         * @var \Cerceau\Resource\Manager
         */
        protected static $Instance;

        protected
            $upstreamConfigTemplate,
            $backendConfigTemplate,
            $backendsHosts,
            $spotsBackends;

        protected function __construct(){}

        /**
         * @static
         * @return \Cerceau\Resource\Manager
         */
        public static function instance(){
            if(!static::$Instance )
                static::$Instance = new static();
            return static::$Instance;
        }

        protected function getUpstreamConfigTemplate(){
            if(!$this->upstreamConfigTemplate ){
                $this->upstreamConfigTemplate = file_get_contents( ROOT . self::NGINX_CONFIGS_TPLS . self::NGINX_UPSTREAM_TPL );
            }
            return $this->upstreamConfigTemplate;
        }

        protected function getBackendConfigTemplate(){
            if(!$this->backendConfigTemplate ){
                $this->backendConfigTemplate = file_get_contents( ROOT . self::NGINX_CONFIGS_TPLS . self::NGINX_BACKEND_TPL );
            }
            return $this->backendConfigTemplate;
        }

        public function generateUpstreamConfig( $upstreamId ){
            $Domain = \Cerceau\System\Registry::instance()->DomainConfig();
            $spotsBackends = $this->db()->selectTable( <<<SQL
    -- SQL_SELECT_RESOURCE_UPSTREAM_SPOTS
    SELECT *
      FROM {{ t("res_spots") }}
      LEFT JOIN {{ t("res_spots_backends") }} USING (spot_id)
      LEFT JOIN {{ t("res_backends") }} USING (backend_id)
      WHERE upstream_id = {{ i(upstream_id) }}
SQL
                , array( 'upstream_id' => $upstreamId )
            );
            $spots = array();
            foreach( $spotsBackends as $backend ){
                if(!isset( $spots[$backend['spot_id']] )){
                    $spots[$backend['spot_id']] = array(
                        'spot_id' => $backend['spot_id'],
                        'spot_host' => $Domain->spot( 'resource', $backend['spot_id'], false ),
                        'backends' => array(),
                    );
                }
                $spots[$backend['spot_id']]['backends'][] = $backend;
            }

            $T = new \Blitz();
            $T->load( $this->getUpstreamConfigTemplate());
            $T->set( array(
                'spots' => array_values( $spots )
            ));
            $File = new \SplFileObject( ROOT . \Cerceau\Config\Constants::NGINX_CONFIGS .'upstream'.  $upstreamId .'.nginx.conf', 'w' );
            $File->fwrite( $T->parse());
        }

        public function generateBackendConfig( $backendId, $backendHost ){
            $T = new \Blitz();
            $T->load( $this->getBackendConfigTemplate());
            $T->set( array(
                'host' => $backendHost
            ));
            $File = new \SplFileObject( ROOT . \Cerceau\Config\Constants::NGINX_CONFIGS .'backend'.  $backendId .'.nginx.conf', 'w' );
            $File->fwrite( $T->parse());
        }

        public function addUpstream( $ip ){
            $result = $this->db()->insert( <<<SQL
    -- SQL_INSERT_RESOURCE_UPSTREAM
    INSERT INTO {{ t("res_upstreams") }}
      ( host ) VALUES
      ( {{ s(host) }} )
      RETURNING *
SQL
                , array( 'host' => $ip )
            );
            if( $result )
                \Cerceau\System\Registry::instance()->Logger()->log( 'servers', 'new upstream #'. $result .' on ip '. $ip );
            return $result;
        }

        public function addBackend( $ip ){
            $backendId = $this->db()->insert( <<<SQL
    -- SQL_INSERT_RESOURCE_BACKEND
    INSERT INTO {{ t("res_backends") }}
      ( host ) VALUES
      ( {{ s(host) }} )
      RETURNING *
SQL
                , array( 'host' => $ip )
            );
            if( $backendId ){
                \Cerceau\System\Registry::instance()->Logger()->log( 'servers', 'new backend #'. $backendId .' on ip '. $ip );

                $this->generateBackendConfig( $backendId, $ip );
            }
            return $backendId;
        }

        public function getSpotBackends( $spotId ){
            $SpotAuthorizer = new \Cerceau\Model\Redis\Authorizer( 'resource_spot' );
            return $SpotAuthorizer->get( $spotId );
        }

        public function getSpotBackendMaster( $spotId ){
            $SpotAuthorizer = new \Cerceau\Model\Redis\Authorizer( 'resource_spot' );
            $backends = $SpotAuthorizer->get( $spotId );
            return reset( $backends );
        }

        public function getSpotId( $resourceId ){
            return intval(( $resourceId - 1 ) / 100000 ) + 1;
        }

        public function addSpot( $upstreamId = 0, $backendsNumber = 3 ){
            if( $upstreamId ){
                if(!$this->db()->selectField( <<<SQL
    -- SQL_CHECK_RESOURCE_UPSTREAM
    SELECT *
      FROM {{ t("res_upstreams") }}
      WHERE upstream_id = {{ upstream }}
SQL
                    , array( 'upstream' => $upstreamId )
                ))
                    $upstreamId = 0;
            }
            if(!$upstreamId ){
                $upstream = $this->db()->selectField( <<<SQL
    -- SQL_GET_RESOURCE_UPSTREAM
    SELECT upstream_id
      FROM (
        SELECT  upstream_id,
                COUNT(spot_id) AS  cnt
          FROM {{ t("res_upstreams") }}
          LEFT JOIN {{ t("res_spots") }} USING (upstream_id)
          GROUP BY upstream_id
          HAVING COUNT(spot_id) < {{ max }}
      ) t
      ORDER BY cnt
      LIMIT 1
SQL
                    , array( 'max' => self::MAX_SPOTS_BY_UPSTREAM )
                );
                if( empty( $upstream ))
                    throw new \Exception( 'Cannot allocate upstream' );
            }

            $spotId = $this->db()->insert( <<<SQL
    -- SQL_INSERT_RESOURCE_SPOT
    INSERT INTO {{ t("res_spots") }}
      ( upstream_id ) VALUES
      ( {{ upstream_id }} )
      RETURNING *
SQL
                , array( 'upstream_id' => $upstreamId )
            );
            if(!$spotId )
                return false;

            \Cerceau\System\Registry::instance()->Logger()->log( 'servers', 'new spot #'. $spotId .' on upstream #'. $upstreamId );

            if( $backendsNumber ){
                $backends = $this->db()->selectTable( <<<SQL
    -- SQL_GET_RESOURCE_BACKENDS
    SELECT  backend_id,
            host
      FROM (
        SELECT  backend_id,
                host,
                COUNT(spot_id) AS  cnt,
                SUM(weight) AS wsum
          FROM {{ t("res_backends") }}
          LEFT JOIN {{ t("res_spots_backends") }} USING (backend_id)
          GROUP BY backend_id
          HAVING COUNT(spot_id) < {{ max }}
      ) t
      ORDER BY cnt, wsum
      LIMIT {{ backends }}
SQL
                    , array(
                        'max' => self::MAX_SPOTS_BY_BACKEND,
                        'backends' => $backendsNumber
                    )
                );

                foreach( $backends as &$backend ){
                    $backend['weight'] = 0;
                    $backend['comma'] = ',';
                }
                $backends[0]['weight'] = 100;
                $backends[0]['comma'] = '';

                if( $this->db()->query( <<<SQL
    -- SQL_SET_RESOURCE_SPOT_BACKENDS
    INSERT INTO {{ t("res_spots_backends") }}
      ( spot_id, backend_id, weight ) VALUES
      {{ BEGIN backends }}
      {{ comma }} ( $spotId, {{ backend_id }}, {{ weight }} )
      {{ END }}
SQL
                    , array( 'backends' => $backends )
                )){
                    \Cerceau\System\Registry::instance()->Logger()->log( 'servers', 'spot backends: #'. implode( ', #', array_map( function( $a ){ return $a['backend_id']; }, $backends )));

                    $allBackends = $this->db()->selectIndexedColumn( <<<SQL
    -- SQL_SELECT_RESOURCE_BACKENDS
    SELECT  backend_id,
            host
      FROM {{ t("res_backends") }}
SQL
                        , array(
                            'max' => self::MAX_SPOTS_BY_BACKEND,
                            'backends' => $backendsNumber
                        )
                    );

                    $spotBackends = array();
                    foreach( $backends as $backend )
                        $spotBackends[] = $backend['host'];

                    $SpotAuthorizer = new \Cerceau\Model\Redis\Authorizer( 'resource_spot' );
                    $SpotAuthorizer->set( $spotId, $spotBackends );
                }else{
                    \Cerceau\System\Registry::instance()->Logger()->log( 'servers-fail', 'cannot create spot backends: #'. implode( ', #', array_map( function( $a ){ return $a['backend_id']; }, $backends )));
                }
            }

            $this->generateUpstreamConfig( $upstreamId );

            return $spotId;
        }

        public function db(){
            return \Cerceau\System\Registry::instance()->DatabaseConnection()->get( 'main' );
        }
    }