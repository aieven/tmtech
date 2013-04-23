<?php
    namespace Cerceau\Model\PostgreSQL;

    class Pagination  extends  \Cerceau\Model\PostgreSQL\Base {

        protected static $params = array(
            'itemsOnPage' => 20,
            'currentPage' => null ,
            'countItems'  => null,
            'countPages'  => null,
        );

        public function __construct( $db = null, &$sql, &$conditions, $itemsOnPage = null ){

            if( $db ){
                $this->db = $db;
            }

            $sql = $sql.'
            {{ IF limitPag }}
            LIMIT {{ limitPag }} OFFSET {{ offsetPag }}
            {{ END }}';
            if( empty( $conditions['page'] )){
                $conditions['page'] = 1;
            }
            $paginationSql = 'SELECT COUNT(tbl.*) FROM ('. $sql . ') tbl';
            $countItems = $this->db()->selectTable( $paginationSql, $conditions );
            self::$params['countItems'] = (int) $countItems[0]['count'];
            if( $itemsOnPage ){
                self::$params['itemsOnPage'] = $itemsOnPage;
            }
            if(( self::$params['countItems'] % self::$params['itemsOnPage'] ) == 0 ){
                self::$params['countPages'] = (int) (self::$params['countItems']/self::$params['itemsOnPage']);
            }
            else{
                self::$params['countPages'] = (int) ( self::$params['countItems']/self::$params['itemsOnPage'] ) + 1;
            }
            self::$params['currentPage'] = $conditions['page'];
            $conditions['limitPag'] = self::$params['itemsOnPage'];
            $conditions['offsetPag'] = self::$params['itemsOnPage'] * ( self::$params['currentPage']-1 );
            unset( $conditions['page'] );
        }

        public static function getPaginationParams(){
            return self::$params;
        }
    }