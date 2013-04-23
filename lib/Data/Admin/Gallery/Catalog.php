<?php
    namespace Cerceau\Data\Admin\Gallery;

    class Catalog extends \Cerceau\Data\Base\Row {

        protected static $db = 'main';

        protected function initialize(){
            self::$fieldsOptions = array(
                'gallery_id' => array(
                    'Int',
                ),
                'published' => array(
                    'Int',
                ),
                'order' => array(
                    'FieldArray',
                    'fieldsOptions' => array(
                        'Int',
                    ),
                ),
            );
            $this->Model = new \Cerceau\Model\PostgreSQL\Admin\Gallery\Catalog();
            parent::initialize();
        }

        /**
         * @var \Cerceau\Model\PostgreSQL\Admin\Gallery\Catalog
         */
        protected $Model;

        /**
         * @return array
         */
        public function selectGalleryCategories(){
            return $this->Model->selectGalleryCategories( $this->export());
        }

        public function deleteCategory(){
            return $this->Model->deleteCategory( $this->export());
        }

        public function publicCategory(){
            return $this->Model->publicCategory( $this->export());
        }

        public function reorderCategories(){
            $ids = array_flip( $this['order']->export());
            if( isset( $ids[1] ))
                unset( $ids[1] );
            $index = 1;
            foreach( $ids as $id => &$data ){
                $data = array(
                    'gallery_id' => $id,
                    'position' => $index++,
                );
            }
            return $this->Model->reorder( array_values( $ids ));
        }

        public function selectGalleryCategoriesApi(){
            $domain = \Cerceau\System\Registry::instance()->DomainConfig()->sub( 'img' );
            $categories = $this->selectGalleryCategories();
            foreach( $categories as &$category ){
                $category['icon'] = $domain . $category['icon'];
                unset( $category['order_id'], $category['published'], $category['deleted'] );
            }
            return $categories;
        }

    }
