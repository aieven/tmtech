<?php
    namespace Cerceau\Controller\Admin;

    class Gallery extends Base {

        protected static $routes = array(
            'get' => array( // method
                'main' => array(
                    'main',
                    'admin/gallery/'
                ),
                'publics' => array(
                    'publics',
                    'admin/gallery/<\d+>/',
                    'params' => array(
                        'gallery_id',
                    ),
                ),
            ),
            'post' => array(
                'uploadTempIcon' => array(
                    'uploadTempIcon',
                    'admin/gallery/temp/upload/',
                    'view'  => 'Json',
                ),
                'saveOrder' => array(
                    'saveOrder',
                    'admin/gallery/order/save/',
                    'view'  => 'Json',
                ),
                'saveCategory' => array(
                    'saveCategory',
                    'admin/gallery/category/save/',
                    'view'      => 'Json',
                ),
                'deleteCategory' => array(
                    'deleteCategory',
                    'admin/gallery/category/delete/',
                    'view' => 'Json',
                ),
                'editCategory' => array(
                    'editCategory',
                    'admin/gallery/category/edit/',
                    'view' => 'Json',
                ),
                'reorder' => array(
                    'reorder',
                    'admin/gallery/categories/reorder/',
                    'view'  => 'Json',
                ),
                'publicCategory' => array(
                    'publicCategory',
                    'admin/gallery/category/public/',
                    'view' => 'Json',
                ),
                'publicAdd' => array(
                    'publicAdd',
                    'admin/gallery/public/add/',
                    'view' => 'Json'
                ),
                'publicEdit' => array(
                    'publicEdit',
                    'admin/gallery/public/edit/',
                    'view' => 'Json'
                ),
                'publicDel' => array(
                    'publicDel',
                    'admin/gallery/public/delete/',
                    'view' => 'Json'
                ),
                'publicRestore' => array(
                    'publicRestore',
                    'admin/gallery/public/restore/',
                    'view' => 'Json'
                ),
            ),
        );


        protected function authorization(){
            return parent::authorization() && $this->Auth->can( \Cerceau\Data\User\Privileges::MODERATION );
        }

        public function pageMain(){
            $Catalog =  new \Cerceau\Data\Admin\Gallery\Catalog();
            $this->View->set( 'categories', $Catalog->selectGalleryCategories());
            $this->View->template( 'admin/gallery' );
            $this->View->globals( 'controller', 'Admin.Gallery' );
            return true;
        }

        public function pageSaveCategory(){
            $ImageUploader = new \Cerceau\Data\Admin\Gallery\GalleryImageUploader();
            $ImageUploader->fetch( \Cerceau\System\Registry::instance()->Request()->post());
            $ImageUploader->upload();

            $Gallery = new \Cerceau\Data\Admin\Gallery\Gallery();
            $Gallery->fetch( \Cerceau\System\Registry::instance()->Request()->post());
            $Gallery['icon'] = $ImageUploader['image_path'];
            $Gallery->create();

            $gallery = $Gallery->export();
            $gallery['icon'] = \Cerceau\System\Registry::instance()->DomainConfig()->sub( 'img' ). $gallery['icon'];
            $this->View->set( 'category', $gallery );
            return true;
        }

        public function pageDeleteCategory(){
            $Catalog = new \Cerceau\Data\Admin\Gallery\Catalog();
            $Catalog->fetch( \Cerceau\System\Registry::instance()->Request()->post());
            $this->View->set( 'done', $Catalog->deleteCategory());
            return true;
        }

        public function pageEditCategory(){
            $post =  \Cerceau\System\Registry::instance()->Request()->post();

            $iconNewPath = false;
            $ImageUploader = new \Cerceau\Data\Admin\Gallery\GalleryImageUploader();
            $ImageUploader->fetch( \Cerceau\System\Registry::instance()->Request()->post());
            if( $ImageUploader->isNotEmpty()){
                $ImageUploader->upload();
                $iconNewPath = $ImageUploader['image_path'];
            }

            $Gallery = new \Cerceau\Data\Admin\Gallery\Gallery();
            if(!$Gallery->load( $post ))
                throw new \Cerceau\Exception\Page();

            $Gallery->merge( $post );
            if( $iconNewPath )
                $Gallery['icon'] = $iconNewPath;
            if(!$Gallery->update())
                throw new \Cerceau\Exception\Page();

            $gallery = $Gallery->export();
            $gallery['icon'] = \Cerceau\System\Registry::instance()->DomainConfig()->sub( 'img' ). $gallery['icon'];
            $this->View->set( 'category', $gallery );
            return true;
        }

        protected function pageReorder(){
            $CategoriesCatalog = new \Cerceau\Data\Admin\Gallery\Catalog();
            $CategoriesCatalog->fetch( \Cerceau\System\Registry::instance()->Request()->post());
            $CategoriesCatalog->reorderCategories();
            // prepare view
            $this->View->set( 'done', 1 );
            return true;
        }

        public function pagePublicCategory(){
            $Catalog = new \Cerceau\Data\Admin\Gallery\Catalog();
            $Catalog->fetch( \Cerceau\System\Registry::instance()->Request()->post());
            $this->View->set( 'done', $Catalog->publicCategory());
			return true;
		}

        protected function pagePublics(){
            $Gallery = new \Cerceau\Data\Admin\Gallery\Gallery();
            if(!$Gallery->load( $this->queryParam()))
                throw new \Cerceau\Exception\Page();

            $Publics = new \Cerceau\Data\Admin\Gallery\Publics\Catalog();
            $Publics->fetch( $this->queryParam());

            $this->View->set( 'gallery', $Gallery->export());
            $this->View->set( 'publics', $Publics->selectPublics());
            $this->View->template( 'admin/gallery_publics' );
            $this->View->globals( 'controller', 'Admin.Gallery.Publics' );
            return true;
        }

        protected function pagePublicAdd(){
            $post = \Cerceau\System\Registry::instance()->Request()->post();
            $Public = new \Cerceau\Data\Admin\Gallery\Publics\Publics();
            $Public->fetch( $this->queryParam() + $post );
            $addedPublic = $Public->addPublic();
            $this->View->set( 'public_id', $addedPublic[0]['public_id'] );
            $this->View->set( 'username', $post['username'] );
            return true;
        }

        protected function pagePublicEdit(){
            $Public = new \Cerceau\Data\Admin\Gallery\Publics\Publics();
            $Public->fetch( $this->queryParam() + \Cerceau\System\Registry::instance()->Request()->post());
            $Public->updatePublicFullName();
            $this->View->set( 'public_id', $Public['public_id'] );
            $this->View->set( 'full_name', $Public['full_name'] );
            return true;
        }

        protected function pagePublicRestore(){
            $Public = new \Cerceau\Data\Admin\Gallery\Publics\Publics();
            $Public->fetch( \Cerceau\System\Registry::instance()->Request()->post());
            $Public->tryParseAgain();
            $this->View->set( 'public_id', $Public['public_id']);
            return true;
        }

        protected function pagePublicDel(){
            $Public = new \Cerceau\Data\Admin\Gallery\Publics\Publics();
            $Public->fetch( \Cerceau\System\Registry::instance()->Request()->post());
            $Public->deletePublic();
            $this->View->set( 'public_id', $Public['public_id']);
            return true;
        }
    }