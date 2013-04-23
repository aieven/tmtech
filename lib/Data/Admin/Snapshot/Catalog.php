<?php

    namespace Cerceau\Data\Admin\Snapshot;

    class Catalog extends \Cerceau\Data\Base\Row {

        /**
         * @var \Cerceau\Model\PostgreSQL\Admin\Snapshot\Catalog
         */
        protected $Model;
        protected static $fieldsOptions = array(
            'snapshot_id' => array(
                'Int',
            ),
        );

        protected function initialize(){
            $this->Model = new \Cerceau\Model\PostgreSQL\Admin\Snapshot\Catalog();
        }

        /**
         * @return array
         */
        public function selectLastSnapshot(){
            $snapshot = $this->Model->selectLastSnapshot( $this->export());
            $snapshot = reset( $snapshot );
            $snapshot['snapshot_data'] = unserialize( $snapshot['snapshot_data'] );
            $charts = $snapshot['snapshot_data']['charts'];
            $newCharts = array();
            foreach( $charts as $id=> $chart ){
                $Public = new \Cerceau\Data\Admin\PeopleAndBrands\Catalog();
                $Public->fetch( array( 'partition' => 'people', 'instagram_id' => $chart ));
                $public = $Public->selectPublics();
                $newCharts[$id] = reset( $public );
            }
            $snapshot['snapshot_data']['charts'] = $newCharts;
            return $snapshot;
        }

        public function selectPublished(){
            $snapshot = $this->Model->selectPublished();
            if(!$snapshot )
                return false;

            $snapshot = reset( $snapshot );
            $snapshot = unserialize( $snapshot['snapshot_data'] );

            foreach( $snapshot['charts'] as &$chart ){
                $Public = new \Cerceau\Data\Admin\PeopleAndBrands\Catalog();
                $Public->fetch( array( 'partition' => 'people', 'instagram_id' => $chart ));
                $public = $Public->selectPublics();
                unset(
                    $public[0]['cat_id'],
                    $public[0]['followers'],
                    $public[0]['subcat_id'],
                    $public[0]['likes'],
                    $public[0]['comments'],
                    $public[0]['photos'],
                    $public[0]['deleted'],
                    $public[0]['parsed_all_old_media']
                );
                $chart = reset( $public );
            }
            $domain = \Cerceau\System\Registry::instance()->DomainConfig()->sub( 'img' );

            $snapshot['top_banners'] =  $snapshot['banners'];
            foreach( $snapshot['top_banners'] as &$topBanner )
                $topBanner['img'] = $domain . $topBanner['img'];
            unset( $snapshot['banners'] );

            $snapshot['bottom_banners'] = $snapshot['tiles'];
            foreach( $snapshot['bottom_banners'] as &$bottomBanner )
                $bottomBanner['img'] = $domain . $bottomBanner['img'];
            unset( $snapshot['tiles'] );
            
            return $snapshot;
        }

        public function publishSnapshot(){
            return $this->Model->publishSnapshot( $this->export());
        }
    }