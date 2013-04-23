<?php
    namespace Cerceau\Controller\Admin;

    class PeopleAndBrands extends \Cerceau\Controller\Admin\Base {
        protected static $routes = array(
            'get' => array( // method
                'categories' => array(
                    'categories',
                    'admin/<brands|people>/',
                    'params' => array(
                        'partition',
                    ),
                ),
                'publics' => array(
                    'publics',
                    'admin/<brands|people>/<\d+>/',
                    'params' => array(
                        'partition',
                        'subcat_id',
                    ),
                ),
                'categoryPublics' => array(
                    'categoryPublics',
                    'admin/<brands|people>/category/<\d+>/',
                    'params' => array(
                        'partition',
                        'cat_id',
                    ),
                ),
                'charts' => array(
                    'charts',
                    'admin/charts/<\d>',
                    'params' => array(
                        'use_method'
                    ),
                ),
                'defaultCharts' => array(
                    'charts',
                    'admin/charts/',
                ),
            ),
            'post' => array( // method
                'subcatAdd' => array(
                    'subcatAdd',
                    'admin/<brands|people>/subcat/add/',
                    'params' => array(
                        'partition',
                    ),
                    'view' => 'Json'
                ),
                'subcatEdit' => array(
                    'subcatEdit',
                    'admin/<brands|people>/subcat/edit/',
                    'params' => array(
                        'partition',
                    ),
                    'view' => 'Json'
                ),
                'subcatDel' => array(
                    'subcatDel',
                    'admin/<brands|people>/subcat/delete/',
                    'params' => array(
                        'partition',
                    ),
                    'view' => 'Json'
                ),
                'publicAdd' => array(
                    'publicAdd',
                    'admin/<brands|people>/public/add/',
                    'params' => array(
                        'partition',
                    ),
                    'view' => 'Json'
                ),
                'publicEdit' => array(
                    'publicEdit',
                    'admin/<brands|people>/public/edit/',
                    'params' => array(
                        'partition',
                    ),
                    'view' => 'Json'
                ),
                'publicDel' => array(
                    'publicDel',
                    'admin/<brands|people>/public/delete/',
                    'params' => array(
                        'partition',
                    ),
                    'view' => 'Json'
                ),
                'publicRestore' => array(
                    'publicRestore',
                    'admin/<brands|people>/public/restore/',
                    'params' => array(
                        'partition',
                    ),
                    'view' => 'Json'
                ),
                'publicData' => array(
                    'publicData',
                    'admin/<brands|people>/public/data/',
                    'params' => array(
                        'partition',
                    ),
                    'view' => 'Json',
                ),
                'saveChartsMethod' => array(
                    'saveChartsMethod',
                    'admin/charts/method/save',
                    'view'  => 'Json',
                ),
            ),
        );

        protected function authorization(){
            return parent::authorization() && $this->Auth->can( \Cerceau\Data\User\Privileges::MODERATION );
        }

        protected function pageCategories(){
            $Cat = new \Cerceau\Data\Admin\PeopleAndBrands\Catalog();
            $Cat->fetch( $this->queryParam());
            $this->View->set( 'partition', $this->queryParam( 'partition' ));
            $this->View->set( 'cat', $Cat->selectCategories());
            $this->View->template( 'admin/people_and_brands' );
            $this->View->globals( 'controller', 'Admin.PeopleAndBrands' );
            return true;
        }

        protected function pageSubcatAdd(){
            $Icon = new \Cerceau\Data\Admin\PeopleAndBrands\IconsImageUploader();
            $Icon->fetch( \Cerceau\System\Registry::instance()->Request()->post());
            $Icon->upload();
            $icon = $Icon->export();
            $icon['type'] = \Cerceau\Data\Image\Types::getExtension( $icon['type'] );
            $post = \Cerceau\System\Registry::instance()->Request()->post();

            $Subcat = new \Cerceau\Data\Admin\PeopleAndBrands\Category();
            $Subcat->fetch( $this->queryParam() + $post + array( 'subcat_icon' => $icon['image_path']));

            $addedSubcat = $Subcat->addSubcat();
            $this->View->set( 'subcat_id', $addedSubcat[0]['subcat_id'] );
            $this->View->set( 'subcat_name', $addedSubcat[0]['subcat_name'] );
            $this->View->set( 'subcat_icon', $addedSubcat[0]['subcat_icon'] );
            $this->View->set( 'cat_id', $post['cat_id'] );
            return true;
        }

        protected function pageSubcatEdit(){
            $post = \Cerceau\System\Registry::instance()->Request()->post();
            $Subcat = new \Cerceau\Data\Admin\PeopleAndBrands\Category();
            if( $_FILES ){
                $Icon = new \Cerceau\Data\Admin\PeopleAndBrands\IconsImageUploader();
                $Icon->fetch( \Cerceau\System\Registry::instance()->Request()->post());
                $Icon->upload();
                $icon = $Icon->export();
                $icon['type'] = \Cerceau\Data\Image\Types::getExtension( $icon['type'] );

                $Subcat->fetch( $this->queryParam() + $post + array( 'subcat_icon' => $icon['image_path'] ));
            }
            else{
                $Subcat->fetch( $this->queryParam() + $post );
            }
            $editedSubcat = $Subcat->editSubcat();
            $this->View->set( 'subcat_id', $editedSubcat[0]['subcat_id'] );
            $this->View->set( 'subcat_name', $editedSubcat[0]['subcat_name'] );
            $this->View->set( 'subcat_icon', $editedSubcat[0]['subcat_icon'] );
            $this->View->set( 'cat_id', $post['cat_id'] );
            return true;
        }

        protected function pageSubcatDel(){
            $post = \Cerceau\System\Registry::instance()->Request()->post();
            $Subcat = new \Cerceau\Data\Admin\PeopleAndBrands\Category();
            $Subcat->fetch( $this->queryParam() + $post );
            $Subcat->deleteSubcat();
            $this->View->set( 'subcat_id', $post['subcat_id']);
            return true;
        }

        protected function pagePublics(){
            $Category = new \Cerceau\Data\Admin\PeopleAndBrands\Category();
            $Category->fetch( $this->queryParam());
            $info = $Category->selectSubcatInfo();

            $Publics = new \Cerceau\Data\Admin\PeopleAndBrands\Catalog();
            $Publics->fetch( $info + $this->queryParam());

            $this->View->set( 'info', $info + $this->queryParam());
            $this->View->set( 'publics', $Publics->selectPublics());
            $this->View->template( 'admin/people_and_brands_publics' );
            $this->View->globals( 'controller', 'Admin.PeopleAndBrands.Publics' );
            return true;
        }

        protected function pageCategoryPublics(){
            $Category = new \Cerceau\Data\Admin\PeopleAndBrands\Category();
            $Category->fetch( $this->queryParam());
            $info = $Category->selectCatInfo();

            $Publics = new \Cerceau\Data\Admin\PeopleAndBrands\Catalog();
            $Publics->fetch( $this->queryParam());

            $this->View->set( 'info', $info + $this->queryParam());
            $this->View->set( 'publics', $Publics->selectPublics());
            $this->View->template( 'admin/people_and_brands_publics' );
            $this->View->globals( 'controller', 'Admin.PeopleAndBrands.Publics' );
            return true;
        }

        protected function pagePublicAdd(){
            $post = \Cerceau\System\Registry::instance()->Request()->post();
            $Public = new \Cerceau\Data\Admin\PeopleAndBrands\Publics();
            $Public->fetch( $this->queryParam() + $post );
            $addedPublic = $Public->addPublic();
            $this->View->set( 'public_id', $addedPublic[0]['public_id'] );
            $this->View->set( 'username', $post['username'] );
            return true;
        }

        protected function pagePublicEdit(){
            $Public = new \Cerceau\Data\Admin\PeopleAndBrands\Publics();
            $Public->fetch( $this->queryParam() + \Cerceau\System\Registry::instance()->Request()->post());
            $Public->updatePublicFullName();
            $this->View->set( 'public_id', $Public['public_id'] );
            $this->View->set( 'full_name', $Public['full_name'] );
            return true;
        }

        protected function pagePublicDel(){
            $post = \Cerceau\System\Registry::instance()->Request()->post();
            $Public = new \Cerceau\Data\Admin\PeopleAndBrands\Publics();
            $Public->fetch( $this->queryParam() + $post );
            $Public->deletePublic();
            $this->View->set( 'public_id', $post['public_id'] );
            return true;
        }

        protected function pagePublicRestore(){
            $post = \Cerceau\System\Registry::instance()->Request()->post();
            $Public = new \Cerceau\Data\Admin\PeopleAndBrands\Publics();
            $Public->fetch( $this->queryParam() + $post );
            $Public->tryParseAgain();
            $this->View->set( 'public_id', $post['public_id'] );
            return true;
        }

        protected function pagePublicData(){
            $Public = new \Cerceau\Data\Admin\PeopleAndBrands\Catalog();
            $Public->fetch( $this->queryParam() + \Cerceau\System\Registry::instance()->Request()->post());
            $public = $Public->selectPublics();
            $public = reset( $public );
            $this->View->set( 'public', $public );
            return true;
        }

        protected function pageCharts(){
            $Publics = new \Cerceau\Data\Admin\PeopleAndBrands\Catalog();
            $Publics->fetch( $this->queryParam() + array(
                'partition' => 'people',
            ));
            $methodGot = $Publics->checkMethod();
            $publics = $Publics->selectPublics();

            // page sort method
            $this->View->set( 'use_method', $Publics['use_method'] );
            $this->View->set( 'use_method_name', $Publics::methodName( $Publics['use_method'] ));

            // current using method
            $Publics->checkMethod( !$methodGot );

            $this->View->set( 'publics', $publics );
            $this->View->set( 'current_method_name', $Publics::methodName( $Publics['use_method'] ));
            $this->View->template( 'admin/charts' );
            $this->View->globals( 'controller', 'Admin.Chart' );
            return true;
        }

        protected function pageSaveChartsMethod(){
            $Publics = new \Cerceau\Data\Admin\PeopleAndBrands\Catalog();
            $Publics->fetch( \Cerceau\System\Registry::instance()->Request()->post());
            if( $Publics->saveMethod()){
                $this->View->set( 'use_method_name', $Publics::methodName( $Publics['use_method'] ));
                $this->View->set( 'result', 'ok' );
            }
            return true;
        }
    }
