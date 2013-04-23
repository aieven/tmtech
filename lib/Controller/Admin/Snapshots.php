<?php
    namespace Cerceau\Controller\Admin;

    class Snapshots extends Base {
        protected static $routes = array(
            'get' => array( // method
                'main'=> array(
                    'main',
                    'admin/snapshot',
                ),
            ),
            'post' => array(
                'save' => array(
                    'save',
                    'admin/snapshot/save',
                    'view' => 'Json',
                ),
                'uploadBanner' => array(
                    'uploadBanner',
                    'admin/snapshot/banner/upload',
                    'view' => 'Json'
                ),
                'editBanner' => array(
                    'editBanner',
                    'admin/snapshot/banner/edit',
                    'view' => 'Json'
                ),
                'uploadTile' => array(
                    'uploadTile',
                    'admin/snapshot/tile/upload',
                    'view' => 'Json'
                ),
                'publish' => array(
                    'publish',
                    'admin/snapshot/publish',
                    'view' => 'Json',
                )
            ),
        );

        protected function authorization(){
            return parent::authorization() && $this->Auth->can( \Cerceau\Data\User\Privileges::MODERATION );
        }

        protected function pageMain(){
            $PeoplePublics = new \Cerceau\Data\Admin\PeopleAndBrands\Catalog();
            $PeoplePublics->fetch( array( 'partition' => 'people' ));
            $publics = $PeoplePublics->selectPublics();

            $Catalog = new \Cerceau\Data\Admin\Snapshot\Catalog();
            $snapshot = $Catalog->selectLastSnapshot();
            // to do some magic
            $this->View->set( 'publics', $publics );
            $this->View->set( 'snapshot', $snapshot );
            $this->View->template( 'admin/snapshot' );
            $this->View->globals( 'controller', 'Admin.Snapshot' );
            $this->View->globals( 'snapshot', $snapshot );
            $this->View->globals( 'img_domain', \Cerceau\System\Registry::instance()->DomainConfig()->sub( 'img' ));
            return true;
        }

        protected function pageSave(){
            $Snapshot = new \Cerceau\Data\Admin\Snapshot\Snapshot();
            $Snapshot->fetch( \Cerceau\System\Registry::instance()->Request()->post());
            if($Snapshot->create())
                $this->View->set( 'snapshot', $Snapshot->export());
            return true;
        }

        protected function pageUploadBanner(){
            try {
                $Banner = new \Cerceau\Data\Admin\Snapshot\BannersImageUploader();
                $Banner->fetch( \Cerceau\System\Registry::instance()->Request()->post());
                $Banner->upload();

            }
            catch( \Cerceau\Exception\FieldValidation $E ){
                throw new \Cerceau\Exception\Client(  $E->getMessage());
            }
            $banner = $Banner->export();
            $banner['type'] = \Cerceau\Data\Image\Types::getExtension( $banner['type'] );
            $this->View->set( 'banner' , $banner);
            return true;
        }

        protected function pageUploadTile(){
            try {
                $post = \Cerceau\System\Registry::instance()->Request()->post();
                switch( $post['tile_type']){
                    case 1:
                        $Tile = new \Cerceau\Data\Admin\Snapshot\TilesWideImageUploader();
                        break;
                    case 2:
                        $Tile = new \Cerceau\Data\Admin\Snapshot\TilesThinImageUploader();
                        break;
                    default:
                        throw new \Cerceau\Exception\Client( 'forms.image.upload.uploadError' );
                }
                $Tile->fetch( $post );
                $Tile->upload();
            }
            catch( \Cerceau\Exception\FieldValidation $E ){
                throw new \Cerceau\Exception\Client(  $E->getMessage());
            }

            $tile = $Tile->export();
            $tile['type'] = \Cerceau\Data\Image\Types::getExtension( $tile['type'] );
            $this->View->set( 'tile' , $tile);
            return true;
        }

        protected function pagePublish(){
            $Catalog = new \Cerceau\Data\Admin\Snapshot\Catalog();
            $post = \Cerceau\System\Registry::instance()->Request()->post();
            $Catalog->fetch( $post );
            $result = $Catalog->publishSnapshot();
            $result = reset( $result );
            if( $post['snapshot_id'] = $result )
                $this->View->set( 'result', 'ok' );
            return true;
        }
    }